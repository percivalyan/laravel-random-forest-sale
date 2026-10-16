
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Periode bulanan
            $table->date('month');

            // Data awal
            $table->string('currency', 3)->default('USD');
            $table->decimal('estimated_avg_price_usd', 12, 2);
            $table->unsignedInteger('sales_result')->default(0);
            $table->unsignedInteger('product_result')->default(0);
            $table->string('price_data_type')->default(
                'synthetic_estimate_not_actual_historical'
            );

            // Input biaya untuk analisis profit/loss
            $table->decimal('cost_price_usd', 12, 2)->nullable();
            $table->decimal('other_costs_usd', 14, 2)->default(0);

            // Fitur hasil perhitungan
            $table->integer('sales_difference_units')->default(0);
            $table->decimal('sales_difference_percent', 10, 2)->nullable();
            $table->decimal('price_difference_usd', 12, 2)->nullable();
            $table->decimal('price_change_percent', 10, 2)->nullable();

            // Pendapatan dan biaya
            $table->decimal('revenue_usd', 16, 2)->default(0);
            $table->decimal('cogs_usd', 16, 2)->default(0);
            $table->decimal('total_cost_usd', 16, 2)->default(0);

            // Hasil keuangan
            $table->decimal('profit_usd', 16, 2)->default(0);
            $table->decimal('loss_usd', 16, 2)->default(0);
            $table->decimal('profit_margin_percent', 10, 2)->nullable();

            // Target klasifikasi
            $table->string('financial_status', 20)->default('unknown');

            $table->timestamps();

            $table->unique(
                ['country_id', 'product_id', 'month'],
                'sales_country_product_month_unique'
            );

            $table->index(['month', 'financial_status']);
            $table->index(['country_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_records');
    }
};
