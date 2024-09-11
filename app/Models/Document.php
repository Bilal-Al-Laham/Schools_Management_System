<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'document_name',
        'document_path'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function subject() : BelongsTo {
        return $this->belongsTo(Subject::class)
        ->withDefault([
            'name' => 'no subjects'
        ]);
    }
}
