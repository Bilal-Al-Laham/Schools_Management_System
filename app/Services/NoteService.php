<?php

namespace App\Services;

use App\Http\Responses\Response;
use App\Repositories\NoteRepository;
use App\Repositories\NoteRepositoryInterface;
use App\Services\SpecialServices\SortingService;
use Illuminate\Http\Request;

interface NoteServiceInterface
{
    public function allNotes(Request $request);
    public function createNote(array $data);


}

class NoteService implements NoteServiceInterface
{
    protected NoteRepository $noteRepository;
    protected $sortingService;

    public function __construct(NoteRepositoryInterface $noteRepositoryInterface, SortingService $sortingService){
        $this->noteRepository = $noteRepositoryInterface;
        $this->sortingService = $sortingService;
    }
    public function allNotes(Request $request){
        try {
            $query = $this->noteRepository->fetchAllNotes();
            $allowedFields = ['id'];
            $this->sortingService->apply($query, $request, $allowedFields, 'id');
            $notes = $query->paginate(10);
            return $notes;
        } catch (\Throwable $th) {
            return Response::Error($th->getMessage(), 'failed to fetch note actually');
        }
    }

    public function createNote(array $data){

    }


}