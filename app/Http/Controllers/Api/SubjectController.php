<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Responses\Response;
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
        // $this->authorize('create', Subject::class);
        $ValidatedData = $request->validated();
        $section = $this->subjectService->createSubject($ValidatedData);
        $message = "section created successfully";
        return Response::Success($section, $message, 201);
    }

    public function show(Subject $subject)
    {
        $sectionItem = $this->subjectService->indexOneSubject($subject);
        $message = "$subject->name retrieved successfully";
        return Response::Success($sectionItem, $message, 200);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        // $this->authorize('update', $subject);
        $validatedData = $request->validated();
        $SubjectItem = $this->subjectService->updateSubject($validatedData, $subject);
        $message = "{$subject->name} updated successfully";
        return Response::Success($SubjectItem, $message, 200);
    }

    public function destroy(Subject $subject)
    {
        // $this->authorize('delete', $subject);
        $SubjectItem = $this->subjectService->deleteSubject($subject);
        $message = "{$subject->name} subject deleted successfully";
        return Response::Success(null, $message, 200);
    }
}
