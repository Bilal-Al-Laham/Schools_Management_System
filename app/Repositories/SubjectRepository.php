<?php

namespace App\Repositories;

use App\Models\SchoolClass;
use App\Models\Subject;

interface SubjectRepositoryInterface
{
    public function getAllSubjects();
    // public function addSubject(array $data);
    public function fetchOneSubject(Subject $subject, $id);
    public function subjects_for_class($schoolClassId);
    // public function deleteSubject();

    public function findById($id);
    public function update($id, array $data);

}
class SubjectRepository implements SubjectRepositoryInterface
{
    public function getAllSubjects() {
        try {
            return Subject::query()
            ->with(['teacher', 'school_class', 'examentions', 'schedules', 'assignments', 'documents']);
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->has('school_class_id')) {
                $query->where('school_class_id', $request->school_class_id);
            }

            if ($request->has('teacher_id')) {
                $query->where('teacher_id', $request->teacher_id);
            }
        } catch (\Throwable $th) {
            throw new \Exception('faild to get subjects' . $th->getMessage());
        }

    }

    public function fetchOneSubject(Subject $subject, $id){
        return $subject->with(['teacher', 'school_class', 'examentions', 'schedules', 'assignments', 'documents'])->findOrFail($id);
    }

    public function findById($id)
    {
        return Subject::findOrFail($id);
    }

    public function update($id, array $data){
        $subject = $this->findById($id);
        $subject->update($data);
        return $subject;
    }

    public function subjects_for_class($schoolClassId)
    {
        return Subject::where('school_class_id', $schoolClassId)->with(['teacher', 'school_class', 'examentions', 'schedules', 'assignments', 'documents'])->get();
    }
}
