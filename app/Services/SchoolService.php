<?php

namespace App\Services;

use App\Repositories\SchoolRepository;
use App\Repositories\SchoolRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

interface SchoolServiceInterface
{
    public function fetchSchools(Request $request);
    public function fetchSchoolItem(Request $request, $id);
    public function createSchool(array $data);
    public function updateSchool(array $data, $id);
    public function deleteSchool($id);

}

class SchoolService implements SchoolServiceInterface
{
    protected SchoolRepository $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    public function fetchSchools(Request $request) {
        return $this->schoolRepository->getSchools($request);
    }

    public function fetchSchoolItem(Request $request, $id) {
        return $this->schoolRepository->getSchoolItem($request, $id);
    }

    public function createSchool(array $data) {
        DB::beginTransaction();
        try {
            $school = $this->schoolRepository->createSchool($data);
            DB::commit();
            return $school;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to create school: ' . $th->getMessage());
            throw new \Exception('An error occurred while creating the school . Please try again');
        }
    }

    public function updateSchool(array $data, $id)
    {
        DB::beginTransaction();
        try {            
            $school = $this->schoolRepository->editSchool($data, $id);
            DB::commit();
            return $school;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to update school: ' . $th->getMessage());
            $php_errormsg = "An error occurred when updating the school";
            throw new \Exception($php_errormsg . ': ' . $th->getMessage(), 500);
        }    
    }

    public function deleteSchool($id)
    {
        return $this->schoolRepository->deleteSchool($id);
    }
}