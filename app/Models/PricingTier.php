<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    protected $fillable = [
        'sales_strategy_id',
        'name',
        'price',
        'features',
        'target_customer','user_id'
    ];

    protected $casts = [
        'features' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salesStrategy()
    {
        return $this->belongsTo(SalesStrategy::class);
    }
}
