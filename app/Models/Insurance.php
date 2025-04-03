<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = ['business_setup_id', 'type', 'provider', 'coverage', 'user_id','annual_cost'];

    public function businessSetup()
    {
        return $this->belongsTo(BusinessSetup::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
