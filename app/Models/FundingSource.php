<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundingSource extends Model
{
    protected $fillable = ['financial_planning_id', 'source', 'type', 'amount', 'status','user_id', 'terms'];

    public function financialPlanning()
    {
        return $this->belongsTo(FinancialPlanning::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
