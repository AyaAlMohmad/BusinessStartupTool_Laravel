<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessIdea extends Model
{
    protected $fillable = ['skills_experience', 'passions_interests', 'values_goals', 'business_ideas','user_id'];

    protected $casts = [
        'skills_experience' => 'array',
        'passions_interests' => 'array',
        'values_goals' => 'array',
        'business_ideas' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
