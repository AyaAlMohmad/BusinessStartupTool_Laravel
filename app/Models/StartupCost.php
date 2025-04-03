<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartupCost extends Model
{
    protected $fillable = ['financial_planning_id','user_id', 'item', 'category', 'amount', 'timing', 'notes'];

    public function financialPlanning()
    {
        return $this->belongsTo(FinancialPlanning::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
