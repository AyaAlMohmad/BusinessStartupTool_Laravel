<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    use Auditable;

    protected $fillable = [
        'id',
        'user_id',
        'business_id',
        'product_feature_id',
        'goal',
        'audience',
        'format',
        'channels',
        'notes'
    ];

    protected $casts = [
        'goal' => 'array',
        'audience' => 'array',
        'format' => 'array',
        'channels' => 'array',
        'notes' => 'array'
    ];
    public function productFeature()
    {
        return $this->belongsTo(ProductFeature::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}