<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueProjection extends Model
{
    protected $fillable = ['financial_planning_id', 'month', 'amount','user_id', 'assumptions'];

    protected $casts = [
        'assumptions' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function financialPlanning()
    {
        return $this->belongsTo(FinancialPlanning::class);
    }
}
