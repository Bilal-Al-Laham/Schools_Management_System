<?php

namespace App\Services;

use App\Http\Responses\Response;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

interface SubjectServiceInterface
{
    public function getAllSubjects(Request $request);
    // public function addSubject(array $data);
    public function fetchOneSubject(Subject $subject, $id);
    public function SubjectsForClass($id);
    public function updateSubject(array $data, Subject $subject);
    // public function deleteSubject();
}


class SubjectService implements SubjectServiceInterface
{
    protected SubjectRepository $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepositoryInterface)
    {
        $this->subjectRepository = $subjectRepositoryInterface;
    }
    public function getAllSubjects(Request $request){
        try {
            $query = $this->subjectRepository->getAllSubjects();
            $sortBy = $request->get('sort_by', 'name');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);
            $subjects = $query->paginate($request->get('per_page', 5));

            $schoolClassName = null;
            if ($request->has('school_class_id')) {
                $schoolClass = SchoolClass::find($request->school_class_id);
                $schoolClassName = $schoolClass ? $schoolClass->name : 'school not found';
            }
            $message = $schoolClassName ? "These are all sections in our school for the schoolclass : {$schoolClassName}" : 'These are all section in our school';

            return Response::Success($subjects, $message, 200);
        } catch (\Throwable $th) {
            $php_errormsg = $th->getMessage();
            throw new \Exception('faild when retrive all sections: '. $php_errormsg);
        }

    }

    public function fetchOneSubject(Subject $subject, $id){
        try {
            $subject = $this->subjectRepository->fetchOneSubject($subject, $id);

                $schoolClass = SchoolClass::find($subject->school_class_id);
                $schoolClassName = $schoolClass ? $schoolClass->name : 'school not found';

            $message = $schoolClassName ? "This is subject that id is $id in our school for the schoolclass : {$schoolClassName}" : "This is subject that id is $id in our school";
            return Response::Success($subject, $message, 200);
        } catch (\Throwable $th) {
            return Response::Error($th->getMessage() . $th->getFile() . $th->getLine(), 500);
        }
    }


    public function updateSubject(array $data, $id){
        $schoolClass = SchoolClass::where('name', $data['school_class_name'])->first();
        $teacher = User::where('name', $data['teacher_name'])->first();

        if (!$schoolClass || !$teacher) {
            throw new \Exception('Invalid class or teacher name provided.');
        }

        return DB::transaction(function () use ($id, $data, $schoolClass, $teacher) {
            return $this->subjectRepository->update($id, [
                'name' => $data['name'],
                'school_class_name' => $schoolClass->name,
                'teacher_name ' => $teacher->name,
            ]);
        });
    }

    public function SubjectsForClass($id)
    {
        try {
            $subjects = $this->subjectRepository->subjects_for_class($id);
            if ($subjects->isEmpty()) {
                return Response::Error('No subjects found for this class.', 404);
            }
            return Response::Success($subjects, 'these are all subjects for this class');
        } catch (\Throwable $th) {
            return Response::Error("here are any faild in fetch subjects for this class : ". $th->getmessage());
        }

    }
}
