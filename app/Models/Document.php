<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    public static function uploadDocument($file, $subjectId){
        $filePath = $file->store('documents', 'public');
        return self::create([
            'subject_id' => $subjectId,
            'document_name' => $file->getClientOriginalName(),
            'document_path' => $filePath
        ]);

    }
    public function getDocumentUrl(){
        return Storage::url($this->document_path);
    }
}
