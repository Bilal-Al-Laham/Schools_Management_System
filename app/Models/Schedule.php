<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'section_id',
        'type',
        'day_of_week',
        'date',
        'start_time',
        'end_time',
        'notes'
    ];

    public function subjects() : BelongsToMany {
        return $this->belongsToMany(Subject::class, 'schedule_subject', 'schedule_id', 'subject_id');
    }

    public function section() : BelongsTo {
        return $this->belongsTo(Section::class);
    }
}
