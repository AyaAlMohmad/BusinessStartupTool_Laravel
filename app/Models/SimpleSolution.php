<?php
namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SimpleSolution extends Model
{
    use Auditable;
    protected $fillable = [
        'big_solution',
        'entry_strategy',
        'things',
        'validation_questions',
        'future_plan',
        'notes',
        'business_id','user_id',
    ];

    protected $casts = [
        'big_solution' => 'array',
        'entry_strategy' => 'array',
        'things' => 'array',
        'validation_questions' => 'array',
        'future_plan' => 'array',
        'notes' => 'array',
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
