<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketResearch extends Model
{
    protected $fillable = [
        'target_customer_name',
        'age',
        'income',
        'education',
        'must_have_solutions',
        'should_have_solutions',
        'nice_to_have_solutions','user_id'
    ];

    protected $casts = [
        'must_have_solutions' => 'array',
        'should_have_solutions' => 'array',
        'nice_to_have_solutions' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
