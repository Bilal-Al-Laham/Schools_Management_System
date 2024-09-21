<?php

namespace App\Services;

use App\Exceptions\MissingRelationException;
use App\Http\Responses\Response;
use App\Models\SchoolClass;
use App\Models\section;
use App\Repositories\SectionRepository;
use App\Repositories\SectionRepositoryInterface;
use App\Rules\SectionBusinessRules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

interface SectionServiceInterface
{
    public function allSections(Request $request);
    public function createSection(array $data);
    public function indexOneSection(section $section);
    public function updateSection(array $data, section $section);
    public function deleteSection(section $section);

}

class SectionService implements SectionServiceInterface
{
    protected SectionRepository $sectionRepository;

    public function __construct(SectionRepositoryInterface $sectionRepositoryInterface)
    {
        $this->sectionRepository = $sectionRepositoryInterface;
    }
    
    public function allSections(Request $request)
    {
        try {
            $query = $this->sectionRepository->getSections();

            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);
            $sections = $query->paginate($request->get('per_page', 5));
            
            $schoolClassName = null;
            if ($request->has('school_class_id')) {
                $schoolClass = SchoolClass::find(id: $request->school_class_id);
                $schoolClassName = $schoolClass ? $schoolClass->name : 'school not found';
            }

            $message = $schoolClassName ? "These are all sections in our school for the schoolclass : {$schoolClassName}" : 'These are all section in our school';

            return Response::Success($sections, $message, 200);
        } catch (\Throwable $th) {
            $php_errormsg = $th->getMessage();
            throw new \Exception('faild when retrive all sections: '. $php_errormsg);
        }
    }

    public function createSection(array $data){
        DB::beginTransaction();
        try {
            $schoolClass = $this->sectionRepository->class_for_section($data);
            SectionBusinessRules::checkMinimumStudents($schoolClass);
            $section = $this->sectionRepository->addSections($data);
            $section['school_class_id'] = $schoolClass->name;
            DB::commit();
            return $section;
        } catch (MissingRelationException $m) {
            DB::rollBack();
            throw new \Exception($m->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create section', ['error' => $th->getMessage()]);
            $php_errormsg = $th->getMessage();
            throw new \Exception('faild to create a section for this class: ' . $php_errormsg);
        }
    }

    public function indexOneSection(section $section){
        try {
            return $this->sectionRepository->fetchOneSection($section);
        } catch (ModelNotFoundException $m) {
            throw new \Exception("Section not found with name:  . {$section->name}", 404);
        } catch (\Throwable $th) {
            throw new \Exception('faild to retrive section: ' . $th->getMessage(), 500);
        }
    }

    public function updateSection(array $data, section $section)
    {
        // $this->authorize('update', $section);
        DB::beginTransaction();
        try {
            $schoolClass = $this->sectionRepository->class_for_section($data);
            if (!$schoolClass) {
                throw new \Exception('school class not found.', 404);
            }

            if (!$schoolClass->students()->exists()) {
                throw new MissingRelationException('no students assigned to this class.');
            }

            SectionBusinessRules::checkMinimumStudents($schoolClass);
            $section->update($data);
            $section['school_class_id'] = $schoolClass->name;
            DB::commit();
            return $section; 

        } catch (MissingRelationException $m) {
            DB::rollBack();
            throw new \Exception($m->getMessage(), 422);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create section', ['error' => $th->getMessage()]);
            $php_errormsg = $th->getMessage();
            throw new \Exception('faild to create a section for this class: ' . $php_errormsg);
        }
    }

    
    public function deleteSection(section $section)
    {
        // $this->authorize('delete', $section);
        DB::beginTransaction();
        try {       
            // if ($section->schedules()->exists() || $section->assignment()->exists()) {
            //     throw new MissingRelationException('Cannot delete section with existing schedules or assignments.');
            // }
            $sectionItem = $section->forceDelete();
            if (!$section) {
                throw new \Exception('school class not found.', 404);
            }
            DB::commit();
            return $sectionItem;
        } catch (MissingRelationException $m) {
            DB::rollBack();
            return Response::Error($m->getMessage(), 422);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to deleted section', ['error' => $th->getMessage()]);
            return Response::Error('Failed to soft-delete the section: ' . $th->getMessage(), 500);
        }
    }
}
