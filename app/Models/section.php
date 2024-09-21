<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'school_class_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function school_class(): BelongsTo {
        return $this->belongsTo(SchoolClass::class)
        ->withDefault([
            'name' => 'no school class'
        ]);
    }

    public function schedules() :HasMany {
        return $this->hasMany(Schedule::class);
    }

    public function assignments() :HasMany {
        return $this->hasMany(Assignment::class);
    }
}
