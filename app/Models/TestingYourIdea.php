<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestingYourIdea extends Model
{
    use HasFactory;
protected $table='testing_your_idea';
    protected $fillable = [
        'user_id',
        'business_id', 
        'solves_problem',
        'problem_statement',
        'existing_solutions_used',
        'current_solutions_details',
        'switch_reason',
        'desirability_notes',
        'required_skills',
        'qualifications_permits',
        'feasibility_notes',
        'payment_possible',
        'profitability',
        'finances_details',
        'viability_notes'
    ];

    protected $casts = [
        'problem_statement' => 'array',
        'current_solutions_details' => 'array',
        'switch_reason' => 'array',
        'desirability_notes' => 'array',
        'required_skills' => 'array',
        'qualifications_permits' => 'array',
        'feasibility_notes' => 'array',
        'payment_possible' => 'array',
        'profitability' => 'array',
        'finances_details' => 'array',
        'viability_notes' => 'array'
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