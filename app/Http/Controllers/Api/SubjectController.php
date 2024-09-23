<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Responses\Response;
use App\Models\section;
use App\Repositories\SectionRepository;
use App\Repositories\SectionRepositoryInterface;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryInterface;
use App\Services\SubjectService;
use App\Services\SubjectServiceInterface;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected SubjectService $subjectService;

    public function __construct(SubjectServiceInterface $subjectServiceInterface, SubjectService $subjectService){
        $this->subjectService = $subjectServiceInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->subjectService->allSubjects($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreSubjectRequest $request)
    {
        // $this->authorize('create', section::class);
        $ValidatedData = $request->validated();
        $section = $this->subjectService->createSubject($ValidatedData);
        $message = "section created successfully";
        return Response::Success($section, $message, 201);
    }

    public function show(Subject $section)
    {
        $sectionItem = $this->subjectService->indexOneSubject($section);
        $message = "$section->name retrieved successfully";
        return Response::Success($sectionItem, $message, 200);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        // $this->authorize('update', $section);
        $validatedData = $request->validated();
        $SubjectItem = $this->subjectService->updateSubject($validatedData, $subject);
        $message = "{$subject->name} updated successfully";
        return Response::Success($SubjectItem, $message, 200);
    }

    public function destroy(Subject $subject)
    {
        // $this->authorize('delete', $section);
        $SubjectItem = $this->subjectService->deleteSubject($subject);
        $message = "Section deleted successfully";
        return Response::Success(null, $message, 200);
    }
}
