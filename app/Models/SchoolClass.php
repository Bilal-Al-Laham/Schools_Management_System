<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year'
    ];


    protected $hidden = ['created_at', 'updated_at'];

    public function subjects() : HasMany {
        return $this->hasMany(Subject::class, 'subject_id');
    }

    public function sections(): HasMany {
        return $this->hasMany(section::class);
    }
    public function students(): HasMany {
        return $this->hasMany(User::class, 'school_class_id');
    }
    public function examentions() :HasMany {
        return $this->hasMany(Examention::class);
    }
}
