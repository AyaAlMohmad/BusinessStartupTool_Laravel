<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use Auditable;

    protected $fillable = [
        'id',
        'user_id',
        'business_id',
   
        'options',
        'notes'
    ];

    protected $casts = [

        'options' => 'array',
        'notes' => 'array'
    ];
    public function marketingCampaigns()
    {
        return $this->hasMany(MarketingCampaign::class);
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