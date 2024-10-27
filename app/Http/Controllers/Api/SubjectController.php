<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Responses\Response;
use App\Models\SchoolClass;
use App\Models\User;
use App\Services\SubjectService;
use App\Services\SubjectServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected SubjectService $subjectService;

    public function __construct(SubjectServiceInterface $subjectServiceInterface) {
        $this->subjectService = $subjectServiceInterface;
    }
    public function index(Request $request)
    {
        return $this->subjectService->getAllSubjects($request);
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
    public function store(StoreSubjectRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject, $id)
    {
        return $this->subjectService->fetchOneSubject($subject, $id);
    }

    public function indexClassSubjects($schoolClassId)
    {
        return $this->subjectService->SubjectsForClass($schoolClassId);
    }


    /*
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, $id)
    {
        logger($request->all());
        try {
            $validatedData = $request->validated();

            $subject = $this->subjectService->updateSubject($validatedData, $id);
            return Response::Success($subject, 'Subject updated successfully', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the subject.' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
