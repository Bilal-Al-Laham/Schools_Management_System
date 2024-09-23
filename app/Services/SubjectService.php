<?php

namespace App\Services;

use App\Exceptions\MissingRelationException;
use App\Http\Responses\Response;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

interface SubjectServiceInterface
{
    public function allSubjects(Request $request);
}

class SubjectService implements SubjectServiceInterface
{
    protected SubjectRepository $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepositoryInterface,SubjectRepository $subjectRepository){
        $this->subjectRepository = $subjectRepositoryInterface;
    }
    public function allSubjects(Request $request)
    {
        try {
            $query = $this->subjectRepository->getSubjects();

            $sortBy = $request->get('sort_by', 'School_class_id');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);
            $subjects = $query->paginate($request->get('per_page', 5));
            
            $schoolClassName = null;
            if ($request->has('school_class_id')) {
                $schoolClass = SchoolClass::find($request->school_class_id);
                $schoolClassName = $schoolClass ? $schoolClass->name : 'school not found';
            }

            $message = $schoolClassName ? "These are all subjects in our school for the schoolClass : {$schoolClassName}" : 'These are all subject in our school';

            return Response::Success($subjects, $message, 200);
        } catch (\Throwable $th) {
            $php_errormsg = $th->getMessage();
            throw new \Exception('failed when retrieve all subjects: '. $php_errormsg);
        }
    }

    public function createSubject(array $data){
        DB::beginTransaction();
        try {
            $schoolClass = $this->subjectRepository->class_for_subject($data);
            // subjectBusinessRules::checkMinimumStudents($schoolClass);
            $subject = $this->subjectRepository->addSubject($data);
            $subject['school_class_id'] = $schoolClass->name;
            DB::commit();
            return $subject;
        } catch (MissingRelationException $m) {
            DB::rollBack();
            throw new \Exception($m->getMessage(), 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create subject', ['error' => $th->getMessage()]);
            $php_errormsg = $th->getMessage();
            throw new \Exception('failed to create a subject for this class: ' . $php_errormsg);
        }
    }

    public function indexOneSubject(Subject $subject){
        try {
            return $this->subjectRepository->fetchOneSubject($subject);
        // } catch (ModelNotFoundException $m) {
        //     throw new \Exception("subject not found with name:"  . "{$subject->name}", 404);
        } catch (\Throwable $th) {
            throw new \Exception('failed to retrieve subject: ' . $th->getMessage(), 500);
        }
    }

    public function updateSubject(array $data, Subject $subject)
    {
        // $this->authorize('update', $subject);
        DB::beginTransaction();
        try {
            // $schoolClass = $this->subjectRepository->class_for_subject($data);
            // if (!$schoolClass) {
            //     throw new \Exception('school class not found.', 404);
            // }

            // if (!$schoolClass->students()->exists()) {
            //     throw new MissingRelationException('no students assigned to this class.');
            // }

            // subjectBusinessRules::checkMinimumStudents($schoolClass);
            $subject->update($data);
            // $subject['school_class_id'] = $schoolClass->name;
            DB::commit();
            return $subject; 

        } catch (MissingRelationException $m) {
            DB::rollBack();
            throw new \Exception($m->getMessage(), 422);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create subject', ['error' => $th->getMessage()]);
            $php_errormsg = $th->getMessage();
            throw new \Exception('failed to create a subject for this class: ' . $php_errormsg);
        }
    }

    
    public function deleteSubject(Subject $subject)
    {
        // $this->authorize('delete', $subject);
        DB::beginTransaction();
        try {       
            // if ($subject->schedules()->exists() || $subject->assignment()->exists()) {
            //     throw new MissingRelationException('Cannot delete subject with existing schedules or assignments.');
            // }
            $subjectItem = $subject->forceDelete();
            if (!$subject) {
                throw new \Exception('school class not found.', 404);
            }
            DB::commit();
            return $subjectItem;
        } catch (MissingRelationException $m) {
            DB::rollBack();
            return Response::Error($m->getMessage(), 422);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to deleted subject', ['error' => $th->getMessage()]);
            return Response::Error('Failed to soft-delete the subject: ' . $th->getMessage(), 500);
        }
    }

}