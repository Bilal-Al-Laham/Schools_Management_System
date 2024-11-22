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
        'description',
        'due_date'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = [
        'subject_name',
        'section_name',
        'teacher_name',
    ];

    public function getSubjectNameAttribute()
    {
        return $this->subject ? $this->subject->name : null;
    }

    public function subject() : BelongsTo {
        return $this->belongsTo(Subject::class, 'subject_id')
        ->withDefault([
            'name' => 'no subject'
        ]);
    }

    public function teacher() : BelongsTo {
        return $this->belongsTo(User::class, 'teacher_id')
        ->withDefault([
            'name' => 'no teacher'
        ]);
    }

    public function section() : BelongsTo {
        return $this->belongsTo(section::class, 'section_id')
        ->withDefault([
            'name' => 'no section'
        ]);
    }


}
