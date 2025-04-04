<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'table_name',
        'record_id',
        'action',
        'old_data',
        'new_data',
        'user_id'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}