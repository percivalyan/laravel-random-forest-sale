<?php

namespace App\Http\Controllers;

use App\Models\SalesRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Rubix\ML\Classifiers\ClassificationTree;
use Rubix\ML\Classifiers\RandomForest;
use Rubix\ML\CrossValidation\Metrics\Accuracy;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Persisters\Filesystem as ModelFilesystem;
use Rubix\ML\Serializers\RBX;
use Throwable;

class RandomForestController extends Controller
{
    /**
     * Lokasi penyimpanan model.
     */
    private const MODEL_PATH = 'app/ml/sales-random-forest.rbx';

    /**
     * Dashboard analisis penjualan.
     */
    public function index()
    {
        $records = SalesRecord::with(['country', 'product'])
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->paginate(15);

        $summary = [
            'total_records' => SalesRecord::count(),

            'revenue' => (float) SalesRecord::sum('revenue_usd'),

            'profit' => (float) SalesRecord::sum('profit_usd'),

            'loss' => (float) SalesRecord::sum('loss_usd'),

            'break_even' => SalesRecord::where(
                'financial_status',
                'break_even'
            )->count(),

            'training_records' => $this->trainingRecords()->count(),
        ];

        $modelExists = is_file(storage_path(self::MODEL_PATH));

        return view('random_forest.index', compact(
            'records',
            'summary',
            'modelExists'
        ));
    }

    /**
     * Training dan evaluasi model Random Forest.
     */
    public function train(Request $request)
    {
        try {
            $records = $this->trainingRecords();

            if ($records->count() < 20) {
                return response()->json([
                    'message' => 'Minimal 20 observasi diperlukan untuk training.',
                    'total_data' => $records->count(),
                    'model_saved' => false,
                ], 422);
            }

            $samples = [];
            $labels = [];

            foreach ($records as $record) {
                $samples[] = $this->features($record);
                $labels[] = $record->financial_status;
            }

            $classes = array_values(array_unique($labels));

            if (count($classes) < 2) {
                return response()->json([
                    'message' => 'Data harus memiliki minimal dua kelas target.',
                    'classes' => $classes,
                    'model_saved' => false,
                ], 422);
            }

            /*
             * Evaluasi holdout acak 80:20.
             * Untuk prediksi masa depan, evaluasi kronologis lebih sesuai.
             */
            $dataset = new Labeled($samples, $labels);
            $dataset->randomize();

            [$train, $test] = $dataset->split(0.8);

            if (count($train) === 0 || count($test) === 0) {
                return response()->json([
                    'message' => 'Data training atau testing kosong.',
                    'model_saved' => false,
                ], 422);
            }

            $trainingClasses = array_unique($train->labels());

            if (count($trainingClasses) < 2) {
                return response()->json([
                    'message' => 'Data training harus memiliki minimal dua kelas.',
                    'training_classes' => array_values($trainingClasses),
                    'model_saved' => false,
                ], 422);
            }

            /*
             * Latih model untuk evaluasi.
             */
            $evaluationModel = $this->newEstimator();
            $evaluationModel->train($train);

            $predictions = $evaluationModel->predict($test);

            $accuracy = (new Accuracy())->score(
                $predictions,
                $test->labels()
            );

            /*
             * Latih ulang model final menggunakan seluruh data.
             * Dengan demikian, model yang disimpan dapat memanfaatkan
             * semua observasi yang tersedia.
             */
            $finalModel = $this->newEstimator();
            $finalModel->train($dataset);

            /*
             * Pastikan direktori penyimpanan tersedia.
             */
            $directory = storage_path('app/ml');

            if (! is_dir($directory)) {
                File::makeDirectory(
                    $directory,
                    0755,
                    true,
                    true
                );
            }

            $modelPath = storage_path(self::MODEL_PATH);

            /*
             * PERBAIKAN:
             * RandomForest harus diserialisasi menjadi Encoding
             * sebelum diberikan kepada Filesystem::save().
             */
            $serializer = new RBX();
            $encoding = $serializer->serialize($finalModel);

            $persister = new ModelFilesystem($modelPath);
            $persister->save($encoding);

            $modelSaved = is_file($modelPath);

            if (! $modelSaved) {
                throw new \RuntimeException(
                    'File model tidak ditemukan setelah proses penyimpanan.'
                );
            }

            return response()->json([
                'message' => 'Training dan evaluasi berhasil.',
                'algorithm' => 'Random Forest Classifier',
                'total_data' => count($samples),
                'training_data' => count($train),
                'testing_data' => count($test),
                'accuracy' => round($accuracy, 4),
                'accuracy_percent' => round($accuracy * 100, 2),
                'classes' => $classes,
                'model_saved' => true,
                'model_path' => self::MODEL_PATH,
                'warning' => 'Akurasi holdout acak bukan jaminan performa '
                    . 'prediksi bulan mendatang.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Training Random Forest gagal.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : 'Terjadi kesalahan saat training.',
                'model_saved' => false,
            ], 500);
        }
    }

    /**
     * Prediksi status keuangan satu data penjualan.
     */
    public function predict(SalesRecord $salesRecord)
    {
        try {
            $modelPath = storage_path(self::MODEL_PATH);

            if (! is_file($modelPath)) {
                return response()->json([
                    'message' => 'Model belum tersedia. Jalankan training terlebih dahulu.',
                ], 422);
            }

            /*
             * PERBAIKAN:
             * Filesystem::load() mengembalikan Encoding.
             * Encoding kemudian dideserialisasi menjadi estimator.
             */
            $persister = new ModelFilesystem($modelPath);
            $encoding = $persister->load();

            $estimator = $encoding->deserializeWith(new RBX());

            $prediction = $estimator->predict(
                new Unlabeled([
                    $this->features($salesRecord),
                ])
            );

            return response()->json([
                'message' => 'Prediksi berhasil.',
                'record_id' => $salesRecord->id,
                'month' => Carbon::parse($salesRecord->month)
                    ->format('Y-m'),
                'country' => $salesRecord->country?->name,
                'product' => $salesRecord->product?->name,
                'actual_status' => $salesRecord->financial_status,
                'predicted_status' => $prediction[0],
                'algorithm' => 'Random Forest Classifier',
                'note' => 'Prediksi tidak mengubah status aktual di database.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Prediksi gagal.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : 'Terjadi kesalahan saat memuat model atau melakukan prediksi.',
            ], 500);
        }
    }

    /**
     * Ambil data yang memenuhi syarat training.
     */
    private function trainingRecords()
    {
        return SalesRecord::query()
            ->whereNotNull('month')
            ->whereNotNull('country_id')
            ->whereNotNull('product_id')
            ->whereNotNull('estimated_avg_price_usd')
            ->whereNotNull('sales_result')
            ->whereNotNull('product_result')
            ->whereNotNull('cost_price_usd')
            ->whereNotNull('other_costs_usd')
            ->whereIn('financial_status', [
                'profit',
                'loss',
                'break_even',
            ])
            ->orderBy('month')
            ->orderBy('id')
            ->get();
    }

    /**
     * Buat estimator Random Forest baru.
     */
    private function newEstimator(): RandomForest
    {
        return new RandomForest(
            new ClassificationTree(5),
            100,
            0.2,
            false
        );
    }

    /**
     * Susunan fitur harus sama pada training dan prediction.
     */
    private function features(SalesRecord $record): array
    {
        $month = Carbon::parse($record->month);

        return [
            (float) $record->country_id,
            (float) $record->product_id,
            (float) $month->format('Y'),
            (float) $month->format('n'),
            (float) $record->estimated_avg_price_usd,
            (float) $record->sales_result,
            (float) $record->product_result,
            (float) $record->cost_price_usd,
            (float) $record->other_costs_usd,
        ];
    }
}
