<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'author',
        'isbn',
        'quantity',
    ];

    public function school() : BelongsTo {
        return $this->belongsTo(School::class);
    } 

}