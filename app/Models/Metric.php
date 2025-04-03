<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $fillable = [
        'mvp_development_id',
        'name',
        'target_value',
        'actual_value',
         'section_id', 'section_type',
         'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function mvpDevelopment()
    {
        return $this->belongsTo(MVPDevelopment::class);
    }
    public function section()
    {
        return $this->morphTo();
    }
}
