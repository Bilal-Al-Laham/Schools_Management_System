<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'content'
    ];

    public function student() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function teacher() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
