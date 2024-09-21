<?php

namespace App\Services;

use App\Repositories\SchoolClassRepository;
use App\Repositories\SchoolClassRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

interface SchoolClassServiceInterface
{
    public function fetchClasses(Request $request);
    public function fetchClassItem(Request $request, $id);
    public function createClass(array $data);
    public function updateClass(array $data, $id);
    public function deleteClass($id);

}

class SchoolClassService implements SchoolClassServiceInterface
{
    protected SchoolClassRepository $schoolClassRepository;

    public function __construct(SchoolClassRepositoryInterface $schoolClassRepositoryInterface)
    {
        $this->schoolClassRepository = $schoolClassRepositoryInterface;
    }

    public function fetchClasses(Request $request) {
        return $this->schoolClassRepository->getClasses($request);
    }

    public function fetchClassItem(Request $request, $id) {
        return $this->schoolClassRepository->getClassItem($request, $id);
    }

    public function createClass(array $data) {
        DB::beginTransaction();
        try {
            $school = $this->schoolClassRepository->addClass($data);
            DB::commit();
            return $school;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create school: ' . $th->getMessage());
            throw new \Exception('An error occurred while creating the school . Please try again');
        }
    }

    public function updateClass(array $data, $id)
    {
        DB::beginTransaction();
        try {            
            $school = $this->schoolClassRepository->editClass($data, $id);
            DB::commit();
            return $school;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to update school: ' . $th->getMessage());
            $php_errormsg = "An error occurred when updating the school";
            throw new \Exception($php_errormsg . ': ' . $th->getMessage(), 500);
        }    
    }

    public function deleteClass($id)
    {
        return $this->schoolClassRepository->removeClass($id);
    }
}