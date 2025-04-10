<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    use Auditable;
    protected $fillable = ['name','user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
