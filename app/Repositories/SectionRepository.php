<?php

namespace App\Repositories;

use App\Exceptions\MissingRelationException;
use App\Models\SchoolClass;
use App\Models\section;

interface SectionRepositoryInterface
{
    public function getSections();
    public function addSections(array $data);
    public function class_for_section(array $data);
    public function fetchOneSection(section $section);
    public function findSection($id);

}
class SectionRepository implements SectionRepositoryInterface
{
    public function getSections() {
        try{
            return section::query()->with(['school_class', 'schedules', 'assignments']);
            if ($request->has('school_class_id')) {
                $query->where('school_class_id', $request->school_class_id);
            }
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            return $section;
        } catch (\Throwable $th) {
            throw new \Exception('faild to get sections' . $th->getMessage());
        }
    }

    public function addSections(array $data){
        try {
            return section::create($data);
        } catch (\Throwable $th) {
            throw new \Exception('faild to create new section in this class: ' . $th->getMessage());
        }
    }

    public function class_for_section(array $data){
        $schoolClass =  SchoolClass::findOrFail($data['school_class_id']);
        if (!$schoolClass) {
            throw new \Exception('school class not found.', 404);
        }
        if (!$schoolClass->students()->exists()) {
            throw new MissingRelationException('No students assigned to this class.');
        }
        return $schoolClass;
    }

    public function fetchOneSection(section $section){
    return section::with(['school_class', 'schedules', 'assignments'])->where('id', $section->id)->firstOrFail();
    }

    public function findSection($id){
        $section = section::findOrFail($id);
        if (!$section) {
            throw new \Exception('school class not found.', 404);
        }

        if (!$section->school_class()->exists()) {
            throw new MissingRelationException('No school Class assigned to this section.');
        }

        if (!$section->schedules()->exists()) {
            throw new MissingRelationException('No schedules assigned to this section.');
        }

        if (!$section->assignments()->exists()) {
            throw new MissingRelationException('No assignments assigned to this section.');
        }
        return $section;
    }
}
