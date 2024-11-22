<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Http\Requests\Assignments\StoreAssignmentRequest;
use App\Http\Requests\Assignments\UpdateAssignmentRequest;
use App\Http\Responses\Response;
use App\Models\section;
use App\Models\Subject;
use App\Models\User;
use App\Repositories\AssignmentRepositoryInterface;
use App\Services\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    protected $assignmentService;
    protected $assignmentRepository;

    public function __construct(AssignmentService $assignmentService, AssignmentRepositoryInterface $assignmentRepositoryInterface) {
        $this->assignmentService = $assignmentService;
        $this->assignmentRepository = $assignmentRepositoryInterface;
    }

    public function index(Request $request)
    {
        return $this->assignmentService->allAssignments($request);
    }


    public function store(StoreAssignmentRequest $request)
    {
        $validateData = $request->validated();
        return $this->assignmentService->createAssignment($validateData);
    }


    public function show($id)
    {
        return $this->assignmentService->showAssignment($id);
    }


    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAssignmentRequest $request, $id)
    {
            $validated = $request->validated();
            return $this->assignmentService->updateAssignment($validated, $id);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->assignmentService->deleteAssignment($id);
    }
}
