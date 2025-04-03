<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingChannel extends Model
{
    protected $fillable = [
        'marketing_id',
        'name',
        'strategy',
        'budget',
        'expected_roi','user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
