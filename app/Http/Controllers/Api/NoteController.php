<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Responses\Response;
use App\Services\NoteService;
use App\Services\NoteServiceInterface;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    protected NoteService $noteService; 

    public function __construct(NoteServiceInterface $noteServiceInterface, NoteService $noteService){
        $this->noteService = $noteServiceInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notes = $this->noteService->allNotes($request);
        $message = 'these are all notes in our school';
        return Response::Success($notes, $message, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $notes = $this->noteService;
        $message = 'these are all messages in our school';
        return Response::Success($notes, $message, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $notes = $this->noteService;
        $message = 'these are all messages in our school';
        return Response::Success($notes, $message, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $notes = $this->noteService;
        $message = 'these are all messages in our school';
        return Response::Success($notes, $message, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $notes = $this->noteService;
        $message = 'these are all messages in our school';
        return Response::Success($notes, $message, 200);
    }
}
