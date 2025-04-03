<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTeam extends Model
{
    protected $fillable = [
        'sales_strategy_id',
        'role',
        'responsibilities',
        'required_skills',
        'target_metrics','user_id'
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
