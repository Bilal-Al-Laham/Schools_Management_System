<?php

namespace App\Repositories;

use App\Models\Note;

interface NoteRepositoryInterface
{
    public function fetchAllNotes();
}
class NoteRepository implements NoteRepositoryInterface
{
    public function fetchAllNotes(){
        return Note::query()->with(['student', 'teacher']);
    }
}