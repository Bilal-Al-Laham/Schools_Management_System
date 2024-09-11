<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'address', 
        'phone_number'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function school_classes() : HasMany {
        return $this->hasMany(SchoolClass::class);
    }

    public function users() : HasMany {
        return $this->hasMany(User::class);
    }

    public function library() : HasOne {
        return $this->hasOne(Library::class);
    }

}
