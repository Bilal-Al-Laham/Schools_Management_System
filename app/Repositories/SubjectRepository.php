<?php

namespace App\Repositories;

use App\Exceptions\MissingRelationException;
use App\Models\SchoolClass;
use App\Models\Subject;

interface SubjectRepositoryInterface
{
    public function getSubjects();
    public function addSubject(array $data);
    public function class_for_subject(array $data);
    public function fetchOneSubject(Subject $section);
    public function findSubject($id);
}
class SubjectRepository implements SubjectRepositoryInterface
{
    public function getSubjects() {
        try{
            return Subject::query()->with(['teacher', 'assignments', 'documents']);
            if ($request->has('school_class_id')) {
                $query->where('school_class_id', $request->school_class_id);
            }
            if ($request->has('teacher_id')) {
                $query->where('teacher_id', $request->teacher_id);
            }
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            return $subject;
        } catch (\Throwable $th) {
            throw new \Exception('failed to get sections' . $th->getMessage());
        }
    }
    public function addSubject(array $data){
        try {
            return Subject::create($data);
        } catch (\Throwable $th) {
            throw new \Exception('failed to create new section in this class: ' . $th->getMessage());
        }
    }

    public function class_for_subject(array $data){
        $schoolClass =  SchoolClass::findOrFail($data['school_class_id']);
        if (!$schoolClass) {
            throw new \Exception('school class not found.', 404);
        }
        if (!$schoolClass->students()->exists()) {
            throw new MissingRelationException('No students assigned to this class.');
        }
        return $schoolClass;
    }
    
    public function fetchOneSubject(Subject $section){
    return Subject::with(['teacher', 'assignments', 'documents'])->where('id', $section->id)->firstOrFail();
    }

    public function findSubject($id){
        $section = Subject::findOrFail($id);
        if (!$section) {
            throw new \Exception('school class not found.', 404);
        }

        if (!$section->school_class()->exists()) {
            throw new MissingRelationException('No school Class assigned to this Subject.');
        }

        if (!$section->schedules()->exists()) {
            throw new MissingRelationException('No schedules assigned to this Subject.');
        }

        if (!$section->assignments()->exists()) {
            throw new MissingRelationException('No assignments assigned to this Subject.');
        }
        return $section;
    }
}