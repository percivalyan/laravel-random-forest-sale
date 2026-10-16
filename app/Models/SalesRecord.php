<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesRecord extends Model
{
    protected $fillable = [
        'country_id',
        'product_id',
        'month',
        'currency',
        'estimated_avg_price_usd',
        'sales_result',
        'product_result',
        'price_data_type',
        'cost_price_usd',
        'other_costs_usd',
        'sales_difference_units',
        'sales_difference_percent',
        'price_difference_usd',
        'price_change_percent',
        'revenue_usd',
        'cogs_usd',
        'total_cost_usd',
        'profit_usd',
        'loss_usd',
        'profit_margin_percent',
        'financial_status',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'estimated_avg_price_usd' => 'decimal:2',
            'cost_price_usd' => 'decimal:2',
            'other_costs_usd' => 'decimal:2',
            'revenue_usd' => 'decimal:2',
            'cogs_usd' => 'decimal:2',
            'total_cost_usd' => 'decimal:2',
            'profit_usd' => 'decimal:2',
            'loss_usd' => 'decimal:2',
            'profit_margin_percent' => 'decimal:2',
            'sales_difference_percent' => 'decimal:2',
            'price_difference_usd' => 'decimal:2',
            'price_change_percent' => 'decimal:2',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}