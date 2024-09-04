<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'date',
        'status',
        'notes'
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}