<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examention extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'school_class_id',
        'exam_date',
        'start_time',
        'end_time',
        'type',
    ];

    protected $hidden = ['created_at', 'updated_at'];


    public function school_class() : BelongsTo {
        return $this->belongsTo(SchoolClass::class)
        ->withDefault([
            'name' => 'no school classes'
        ]);
    }


    public function subjects() : BelongsTo {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function examResult() : HasMany {
        return $this->hasMany(ExamResult::class);
    }
}
