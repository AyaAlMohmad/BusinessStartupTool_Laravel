<?php
namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
class BusinessIdea extends Model
{
    use Auditable;
    protected $fillable = ['skills_experience','personal_notes','business_id', 'passions_interests', 'values_goals', 'business_ideas','user_id'];

    protected $casts = [
        'skills_experience' => 'array',
        'passions_interests' => 'array',
        'values_goals' => 'array',
        'business_ideas' => 'array',
        'personal_notes'=>'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
