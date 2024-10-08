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

    protected $hidden = ['created_at', 'updated_at'];

    public function student() : BelongsTo {
        return $this->belongsTo(User::class, 'student_id')
        ->withDefault([
            'name' => 'no student'
        ]);
    }

    public function teacher() : BelongsTo {
        return $this->belongsTo(User::class)
        ->withDefault([
            'name' => 'no teacher'
        ]);
    }
}
