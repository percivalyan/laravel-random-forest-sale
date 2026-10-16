<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Product;
use App\Models\SalesRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalesRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesRecord::with(['country', 'product'])
            ->orderByDesc('month')
            ->orderByDesc('id');

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->integer('country_id'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('start_month')) {
            $start = Carbon::createFromFormat(
                '!Y-m',
                $request->input('start_month')
            )->startOfMonth()->toDateString();

            $query->whereDate('month', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::createFromFormat(
                '!Y-m',
                $request->input('end_month')
            )->endOfMonth()->toDateString();

            $query->whereDate('month', '<=', $end);
        }

        $records = $query->paginate(15)->withQueryString();

        $countries = Country::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $summary = [
            'total_records' => SalesRecord::count(),
            'revenue' => (float) SalesRecord::sum('revenue_usd'),
            'profit' => (float) SalesRecord::sum('profit_usd'),
            'loss' => (float) SalesRecord::sum('loss_usd'),
        ];

        return view('sales_records.index', compact(
            'records',
            'countries',
            'products',
            'summary'
        ));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales_records.create', compact(
            'countries',
            'products'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRecord($request);

        DB::transaction(function () use ($validated) {
            SalesRecord::create($validated);
            $this->recalculateAll();
        });

        return redirect()
            ->route('sales-records.index')
            ->with('success', 'Data penjualan berhasil ditambahkan.');
    }

    public function show(SalesRecord $salesRecord)
    {
        $salesRecord->load(['country', 'product']);

        return view('sales_records.show', compact('salesRecord'));
    }

    public function edit(SalesRecord $salesRecord)
    {
        $countries = Country::orderBy('name')->get();

        $products = Product::orderBy('name')->get();

        return view('sales_records.edit', compact(
            'salesRecord',
            'countries',
            'products'
        ));
    }

    public function update(Request $request, SalesRecord $salesRecord)
    {
        $validated = $this->validateRecord($request, $salesRecord);

        DB::transaction(function () use ($salesRecord, $validated) {
            $salesRecord->update($validated);
            $this->recalculateAll();
        });

        return redirect()
            ->route('sales-records.index')
            ->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy(SalesRecord $salesRecord)
    {
        DB::transaction(function () use ($salesRecord) {
            $salesRecord->delete();
            $this->recalculateAll();
        });

        return redirect()
            ->route('sales-records.index')
            ->with('success', 'Data penjualan berhasil dihapus.');
    }

    private function validateRecord(
        Request $request,
        ?SalesRecord $record = null
    ): array {
        $monthRule = Rule::unique('sales_records', 'month')
            ->where('country_id', $request->input('country_id'))
            ->where('product_id', $request->input('product_id'));

        if ($record) {
            $monthRule->ignore($record->id);
        }

        return $request->validate([
            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],

            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'month' => [
                'required',
                'date_format:Y-m-d',
                $monthRule,
            ],

            'currency' => [
                'required',
                'string',
                'size:3',
            ],

            'estimated_avg_price_usd' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sales_result' => [
                'required',
                'integer',
                'min:0',
            ],

            'product_result' => [
                'required',
                'integer',
                'min:0',
            ],

            'price_data_type' => [
                'required',
                'string',
                'max:100',
            ],

            'cost_price_usd' => [
                'required',
                'numeric',
                'min:0',
            ],

            'other_costs_usd' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);
    }

    /**
     * Recalculate all financial fields.
     */
    public function recalculate(Request $request)
    {
        $result = DB::transaction(function () {
            $this->recalculateAll();

            return [
                'total_records' => SalesRecord::count(),
                'revenue' => (float) SalesRecord::sum('revenue_usd'),
                'profit' => (float) SalesRecord::sum('profit_usd'),
                'loss' => (float) SalesRecord::sum('loss_usd'),
            ];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Perhitungan berhasil diperbarui.',
                'summary' => $result,
            ]);
        }

        return redirect()
            ->route('random-forest.index')
            ->with('success', 'Perhitungan berhasil diperbarui.');
    }

    /**
     * Recalculate records in chronological order.
     *
     * Sales and cost formulas follow the current application rules.
     */
    private function recalculateAll(): void
    {
        $records = SalesRecord::query()
            ->orderBy('country_id')
            ->orderBy('product_id')
            ->orderBy('month')
            ->orderBy('id')
            ->get();

        $previousPrices = [];

        foreach ($records as $record) {
            $key = $record->country_id . ':' . $record->product_id;

            $price = (float) $record->estimated_avg_price_usd;
            $sales = (int) $record->sales_result;
            $units = (int) $record->product_result;
            $costPrice = (float) $record->cost_price_usd;
            $otherCosts = (float) $record->other_costs_usd;

            // Revenue and costs.
            $revenue = round($sales * $price, 2);
            $cogs = round($sales * $costPrice, 2);
            $totalCost = round($cogs + $otherCosts, 2);
            $net = round($revenue - $totalCost, 2);

            $previousPrice = $previousPrices[$key] ?? null;
            $difference = $sales - $units;

            $record->sales_difference_units = $difference;

            $record->sales_difference_percent = $units > 0
                ? round(($difference / $units) * 100, 2)
                : null;

            $record->price_difference_usd = $previousPrice !== null
                ? round($price - $previousPrice, 2)
                : null;

            $record->price_change_percent = (
                $previousPrice !== null && $previousPrice != 0
            )
                ? round(
                    (($price - $previousPrice) / $previousPrice) * 100,
                    2
                )
                : null;

            $record->revenue_usd = $revenue;
            $record->cogs_usd = $cogs;
            $record->total_cost_usd = $totalCost;

            // Keep profit and loss as non-negative separate amounts.
            $record->profit_usd = max(0, $net);
            $record->loss_usd = max(0, -$net);

            $record->profit_margin_percent = $revenue > 0
                ? round(($net / $revenue) * 100, 2)
                : null;

            $record->financial_status = $net > 0
                ? 'profit'
                : ($net < 0 ? 'loss' : 'break_even');

            $record->save();

            // The next record for this country-product pair
            // uses this month's price as its previous price.
            $previousPrices[$key] = $price;
        }
    }
}