<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'section_id',
        'title',
        'due_date'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function subject() : BelongsTo {
        return $this->belongsTo(Subject::class)
        ->withDefault([
            'name' => 'no subjects'
        ]);
    }

    public function teacher() : BelongsTo {
        return $this->belongsTo(User::class, 'taecher_id')
        ->withDefault([
            'name' => 'no taechers'
        ]);
    }

    public function section() : BelongsTo {
        return $this->belongsTo(section::class)
        ->withDefault([
            'name' => 'no sections'
        ]);
    }
}
