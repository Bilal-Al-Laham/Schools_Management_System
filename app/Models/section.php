<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_class_id'
    ];

    public function school_class(): BelongsTo {
        return $this->belongsTo(SchoolClass::class)
        ->withDefault([
            'name' => 'no school class'
        ]);
    }

    public function schedules() :HasMany {
        return $this->hasMany(Schedule::class);
    }

    public function assignment() :HasMany {
        return $this->hasMany(Assignment::class);
    }
}
