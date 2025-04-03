<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesChannel extends Model
{
    protected $fillable = [
        'sales_strategy_id',
        'name',
        'description',
        'target_revenue',
        'commission_structure','user_id'
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
