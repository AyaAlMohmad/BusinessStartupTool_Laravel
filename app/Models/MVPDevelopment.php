<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MVPDevelopment extends Model
{
    protected $table = 'mvp_developments';
protected $fillable=['user_id'];
    public function features()
    {
        return $this->hasOne(Feature::class,'mvp_development_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assumptions()
    {
        return $this->hasMany(Assumption::class,'mvp_development_id');
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class,'mvp_development_id');
    }

    public function metrics()
    {
        return $this->hasMany(Metric::class,'mvp_development_id');
    }
}
