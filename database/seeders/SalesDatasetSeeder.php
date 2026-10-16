<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Product;
use App\Models\SalesRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesDatasetSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $countries = [
                ['Mexico', 'MEX'],
                ['Peru', 'PER'],
                ['Hong Kong', 'HKG'],
                ['Israel', 'ISR'],
                ['Iraq', 'IRQ'],
                ['Chile', 'CHL'],
                ['Colombia', 'COL'],
                ['India', 'IND'],
                ['Morocco', 'MAR'],
            ];

            foreach ($countries as [$name, $code]) {
                Country::updateOrCreate(
                    ['name' => $name],
                    ['code' => $code]
                );
            }

            $product = Product::updateOrCreate(
                [
                    'name' => 'LG Two-Door Refrigerator',
                    'brand' => 'LG',
                    'door_type' => 'Two-door',
                ],
                [
                    'category' => 'Refrigerator',
                    'capacity' => '500 liters and above',
                    'is_active' => true,
                ]
            );

            // Format setiap baris:
            // bulan, harga USD, unit terjual, unit tersedia/produksi
            $dataset = [
                'Mexico' => [
                    ['2024-01', 640.25, 3173, 2576],
                    ['2024-02', 644.80, 896, 936],
                    ['2024-03', 650.00, 1655, 1614],
                    ['2024-04', 655.20, 1339, 1180],
                    ['2024-05', 659.75, 731, 1528],
                    ['2024-06', 663.00, 2487, 3353],
                ],
                'Peru' => [
                    ['2024-01', 512.20, 10081, 9563],
                    ['2024-02', 515.84, 3766, 3820],
                    ['2024-03', 520.00, 5853, 6820],
                    ['2024-04', 524.16, 5235, 4512],
                    ['2024-05', 527.80, 2853, 4808],
                    ['2024-06', 530.40, 5466, 3734],
                ],
                'Hong Kong' => [
                    ['2024-01', 591.00, 0, 228],
                    ['2024-02', 595.20, 610, 318],
                    ['2024-03', 600.00, 301, 503],
                    ['2024-04', 604.80, 229, 30],
                    ['2024-05', 609.00, 652, 778],
                    ['2024-06', 612.00, 354, 570],
                ],
                'Israel' => [
                    ['2024-01', 689.50, 2417, 1643],
                    ['2024-02', 694.40, 220, 170],
                    ['2024-03', 700.00, 1216, 1593],
                    ['2024-04', 705.60, 1188, 816],
                    ['2024-05', 710.50, 854, 1386],
                    ['2024-06', 714.00, 1502, 1032],
                ],
                'Iraq' => [
                    ['2024-01', 571.30, 0, 0],
                    ['2024-02', 575.36, 762, 958],
                    ['2024-03', 580.00, 427, 573],
                    ['2024-04', 584.64, 1078, 998],
                    ['2024-05', 588.70, 339, 226],
                    ['2024-06', 591.60, 1873, 3434],
                ],
                'Chile' => [
                    ['2024-01', 581.15, 710, 501],
                    ['2024-02', 585.28, 400, 333],
                    ['2024-03', 590.00, 910, 1423],
                    ['2024-04', 594.72, 1316, 954],
                    ['2024-05', 598.85, 1000, 1983],
                    ['2024-06', 601.80, 1525, 719],
                ],
                'Colombia' => [
                    ['2024-01', 541.75, 1553, 1517],
                    ['2024-02', 545.60, 833, 146],
                    ['2024-03', 550.00, 2076, 2463],
                    ['2024-04', 554.40, 931, 949],
                    ['2024-05', 558.25, 2520, 3129],
                    ['2024-06', 561.00, 2130, 3094],
                ],
                'India' => [
                    ['2024-01', 472.80, 0, 1153],
                    ['2024-02', 476.16, 1648, 1040],
                    ['2024-03', 480.00, 825, 551],
                    ['2024-04', 483.84, 766, 939],
                    ['2024-05', 487.20, 990, 1031],
                    ['2024-06', 489.60, 1210, 755],
                ],
                'Morocco' => [
                    ['2024-01', 551.60, 525, 294],
                    ['2024-02', 555.52, 289, 701],
                    ['2024-03', 560.00, 1333, 999],
                    ['2024-04', 564.48, 413, 393],
                    ['2024-05', 568.40, 165, 439],
                    ['2024-06', 571.20, 331, 0],
                ],
            ];

            $countryIndex = 0;

            foreach ($dataset as $countryName => $rows) {
                $country = Country::where('name', $countryName)
                    ->firstOrFail();

                $previousPrice = null;

                foreach ($rows as $monthIndex => $row) {
                    [$month, $price, $sales, $productUnits] = $row;

                    // Asumsi biaya modal sintetis: 78%–96% harga jual.
                    // Pola deterministik agar hasil seeder konsisten.
                    $costRatio = 0.78
                        + (($countryIndex + $monthIndex) % 7) * 0.03;

                    $costPrice = round($price * $costRatio, 2);

                    // Asumsi biaya operasional: 4%–12% pendapatan.
                    $operatingRatio = 0.04
                        + (($countryIndex * 2 + $monthIndex) % 5) * 0.02;

                    $revenue = round($sales * $price, 2);
                    $cogs = round($sales * $costPrice, 2);
                    $otherCosts = round(
                        $revenue * $operatingRatio,
                        2
                    );

                    $totalCost = round($cogs + $otherCosts, 2);
                    $netResult = round($revenue - $totalCost, 2);

                    $salesDifference = $sales - $productUnits;

                    $salesDifferencePercent = $productUnits > 0
                        ? round(
                            ($salesDifference / $productUnits) * 100,
                            2
                        )
                        : null;

                    $priceDifference = $previousPrice !== null
                        ? round($price - $previousPrice, 2)
                        : null;

                    $priceChangePercent = (
                        $previousPrice !== null && $previousPrice != 0
                    )
                        ? round(
                            (($price - $previousPrice) / $previousPrice)
                                * 100,
                            2
                        )
                        : null;

                    SalesRecord::updateOrCreate(
                        [
                            'country_id' => $country->id,
                            'product_id' => $product->id,
                            'month' => $month . '-01',
                        ],
                        [
                            'currency' => 'USD',
                            'estimated_avg_price_usd' => $price,
                            'sales_result' => $sales,
                            'product_result' => $productUnits,
                            'price_data_type' =>
                                'synthetic_estimate_not_actual_historical',

                            'cost_price_usd' => $costPrice,
                            'other_costs_usd' => $otherCosts,

                            'sales_difference_units' => $salesDifference,
                            'sales_difference_percent' =>
                                $salesDifferencePercent,
                            'price_difference_usd' => $priceDifference,
                            'price_change_percent' => $priceChangePercent,

                            'revenue_usd' => $revenue,
                            'cogs_usd' => $cogs,
                            'total_cost_usd' => $totalCost,

                            'profit_usd' => max(0, $netResult),
                            'loss_usd' => max(0, -$netResult),

                            'profit_margin_percent' => $revenue > 0
                                ? round(
                                    ($netResult / $revenue) * 100,
                                    2
                                )
                                : null,

                            'financial_status' => $netResult > 0
                                ? 'profit'
                                : ($netResult < 0 ? 'loss' : 'break_even'),
                        ]
                    );

                    $previousPrice = $price;
                }

                $countryIndex++;
            }
        });
    }
}
