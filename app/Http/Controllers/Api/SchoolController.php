<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Responses\Response;
use App\Repositories\SchoolRepository;
use App\Repositories\SchoolRepositoryInterface;
use App\Services\SchoolService;
use App\Services\SchoolServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    protected SchoolService $schoolService;
    
    public function __construct(SchoolService $schoolService) {
        $this->schoolService = $schoolService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $school = $this->schoolService->fetchSchools($request);

            $message = 'These are all schools sorted by '. $request->get('sort_by', 'name');
            return Response::Success($school, $message, 200); 

        } catch (Exception $exception) {
            $php_errormsg = 'error when retrive schools : ' . $exception->getMessage();
            return Response::Error($php_errormsg . $exception->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolRequest $request)
    {
        try {
            if (!$request->has(['name', 'address', 'phone_number'])) {
                return Response::Error('missing required fields', 422);
            }

            $school = $this->schoolService->createSchool($request->validated());

            $message = "{$school->name} school created successfully";
            return Response::Success($school, $message, 201);
            
        } catch (Exception $e) {
            return Response::Error($e->getMessage(), 409) ;
        } catch (\Throwable $th) {
            $php_errormsg = "An error occurred when creating the school";
            return Response::Error($php_errormsg . ': ' . $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $school = $this->schoolService->fetchSchoolItem($request, $id);

            $message = __('school "(' . $school['data']->name . ')" details retrieved seccessfully');
            return Response::Success($school, $message);

        }catch (ModelNotFoundException){
            return Response::Error('school not found !!', 404);

        } catch (Exception $exception){
            $php_errormsg = 'An error occurred while retrieving school details';
            return Response::Error($php_errormsg . ': ' . $exception->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolRequest $request, $id)
    {
        try {
            $validatedData = $request->only(array_filter($request->all()));
            
            if (isset($validatedData['phone_number']) && School::where('phone_number', $validatedData['phone_number'])->exists()) {
                return Response::Error('this phone number alredy exists for another school', 409);
            }

            $school = $this->schoolService->updateSchool($validatedData, $id);

            $message = "shool updated successfully";
            return Response::Success($school, $message, 200);

        } catch (ModelNotFoundException $th) {
            DB::rollBack();
            Log::error('school not found: ' . $th->getMessage());
            return Response::Error('An error occurred while updating the school!!... please try again.' . $th->getMessage());

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to update school: ' . $th->getMessage());
            $php_errormsg = "An error occurred when updating the school";
            return Response::Error($php_errormsg . ': ' . $th->getMessage(), 500);
        }    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $school = $this->schoolService->deleteSchool($id);
            $message = "school deleted successfully";
            return Response::Success(null, $message, 200);

        } catch (ModelNotFoundException $e) {
            Log::error("School not found: {$id}");
            return Response::Error('School not found', 404);

        } catch (\Throwable $th) {
            Log::error('Failed to delete school: ' . $th->getMessage());
            return Response::Error('An error occurred. Please try again: ' . $th->getMessage(), 500);
        }
    }
}
