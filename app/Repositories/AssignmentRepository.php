<?php

namespace App\Repositories;

use App\Models\Assignment;

interface AssignmentRepositoryInterface
{
    public function getAllAssignment($filters = []);
    public function createAssignment(array $data);
    public function findAssignmentById($id);
    public function updateAssignment(array $data, $id);
    public function deleteAssignment($id);
}
class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function getAllAssignment($filters = []){
        return Assignment::with(['subject', 'teacher', 'section'])
        ->when(isset($filters['teacher_id']), fn($query) => $query->where('teacher_id', $filters['teacher_id']));
    }

    public function createAssignment(array $data){
        return Assignment::create($data);
    }

    public function findAssignmentById($id){
        return Assignment::with(['subject', 'section', 'teacher'])->find($id);
    }

    public function updateAssignment(array $data, $id){
        return $this->findAssignmentById($id)->update($data);
    }

    public function deleteAssignment($id){
        return $this->findAssignmentById($id)->delete();
    }
}
