<?php

namespace App\Services;

use App\Actions\AssignmentsWithAttributes;
use App\Actions\CloseAssignmentAction;
use App\Exceptions\AssignmentsLogicException;
use App\Http\Middleware\CheckTeacherMiddleware;
use App\Http\Middleware\CheckUserLoggedIn;
use App\Http\Resources\AssignmentResource;
use App\Http\Responses\Response;
use App\Models\Assignment;
use App\Models\section;
use App\Models\Subject;
use App\Models\User;
use App\Repositories\AssignmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentService
{
    protected $assignmentRepository;

    public function __construct(AssignmentRepositoryInterface $assignmentRepositoryInterface){
        $this->assignmentRepository = $assignmentRepositoryInterface;
    }

    public function allAssignments(Request $request){
        try {
            $subject_name = $request->input('subject_name');

            $query = $this->assignmentRepository->getAllAssignment();

            if ($subject_name) {
                $query->whereHas('subject', function ($query) use ($subject_name){
                    $query->where('name', $subject_name);
                });
            }
            $assignments = $query->orderBy('section_id')->get();
            $message = 'there are all assignments';
                return response()->json([
                    'message' => $message,
                    'assignments' => AssignmentResource::collection($assignments)
                ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
            return Response::Error($th->getMessage(), 500);
        }
    }

    public function createAssignment(array $data){
        try {
            CheckUserLoggedIn::class;
            CheckTeacherMiddleware::class;

            $subject = Subject::where('name', $data['subject_name'])->first();
            $section = $data['section_name']
            ? section::where('name', $data['section_name'])->first()
            : null;

            if ($data['section_name'] && !$section) {
                return response()->json(['error' => 'Section not found'], 404);
            }

            $teacher = User::where('name', $data['teacher_name'])->first();

            if (!$subject || !$teacher) {
                throw new AssignmentsLogicException();
            }

            $assignment = $this->assignmentRepository->createAssignment([
                'title' => $data['title'],
                'description' => $data['description'],
                'due_date' => $data['due_date'],
                'subject_id' => $subject->id,
                'section_id' => $section?->id,
                'teacher_id' => Auth::id(),
            ]);

            $assignment->load([
                'subject',
                'section',
                'teacher',
            ]);

            $message = 'Assignment created successfuly';
            return response()->json([
                'message' => $message,
                'assignment :' =>  new AssignmentResource($assignment)],
                status: 201);

        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
            return Response::Error($th->getMessage(), 500);
        }
    }

    public function showAssignment($id){
        try {
            $assignment = $this->assignmentRepository->findAssignmentById($id);
            $message = "this is the $assignment->name assignament for subject";
            return response()->json([
                'message' => $message,
                'Assignment' => new AssignmentResource($assignment)
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
            $error = 'fails when retrive this assignment';
            return Response::Error($error . ": " . $th->getMessage(), 500);
        }
    }

    public function updateAssignment(array $data, $id)
    {
        try {
            CheckUserLoggedIn::class;
            CheckTeacherMiddleware::class;
            $assignment = $this->assignmentRepository->findAssignmentById($id);
            AssignmentsLogicException::assignmentNotFound($assignment);

            $subject = Subject::where('name', $data['subject_name'])->first();
            $section = $data['section_name']
                ? Section::where('name', $data['section_name'])->first()
                : null;
            $teacher = User::where('name', $data['teacher_name'])->first();

            if (!$subject || !$teacher) {
                AssignmentsLogicException::subjectOrTeacherNotFound($subject, $teacher);
            }

            $this->assignmentRepository->updateAssignment([
                'title' => $data['title'],
                'description' => $data['description'],
                'due_date' => $data['due_date'],
                'subject_id' => $subject->id,
                'section_id' => $section?->id,
                'teacher_id' => Auth::id(),
            ], $id);

            $assignment->load([
                'subject',
                'section',
                'teacher',
            ]);

            return Response::Success( new AssignmentResource($assignment), 'Assignment updated successfuly');
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }



    public function deleteAssignment($id){
        try {
            CheckUserLoggedIn::class;

            $assignment = $this->assignmentRepository->deleteAssignment($id);

            return response()->json( data: [
                'data' => 'Assignment deleted successfully']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
            return Response::Error( 'Error: '. $th->getMessage());
        }
    }
}
