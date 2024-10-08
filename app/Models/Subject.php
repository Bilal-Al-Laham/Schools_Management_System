<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_class_id',
        'teacher_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function school_class() : BelongsToMany {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'school_class_id');
    }

    public function teacher() : BelongsTo {
        return $this->belongsTo(User::class, 'teacher_id')
        ->withDefault([
            'name' => 'no teacher'
        ]);
    }

    public function examentions() : BelongsToMany {
        return $this->belongsToMany(Examention::class, 'subject_examention', 'subject_id', 'examention_id');
    }

    public function schedules(): BelongsToMany {
        return $this->belongsToMany(Schedule::class, 'schedule_subject', 'subject_id', 'schedule_id');
    }    

    public function assignments() :HasMany {
        return $this->hasMany(Assignment::class);
    }

    public function documents() :HasMany {
        return $this->hasMany(Document::class);
    }

}
