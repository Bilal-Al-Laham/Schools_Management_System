<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'examention_id',
        'score'
    ];

    public function examention() : BelongsTo {
        return $this->belongsTo(Examention::class)
        ->withDefault([
            'name' => 'no examentions'
        ]);
    }

    public function student() : BelongsTo {
        return $this->belongsTo(User::class, 'student_id')
        ->withDefault([
            'name' => 'no students'
        ]);
    }
}
