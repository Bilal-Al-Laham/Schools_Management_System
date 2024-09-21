<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;
use App\Http\Responses\Response;
use App\Services\SchoolClassService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolClassController extends Controller
{
    protected SchoolClassService $schoolClassService;
    
    public function __construct(SchoolClassService $schoolClassService) {
        $this->schoolClassService = $schoolClassService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $school = $this->schoolClassService->fetchClasses($request);

            $message = 'These are all school Classes sorted by '. $request->get('sort_by', 'name');
            return Response::Success($school, $message, 200); 

        } catch (\Throwable $exception) {
            $php_errormsg = 'error when retrive Classes : ' . $exception->getFile(). $exception->getLine();
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
    public function store(StoreSchoolClassRequest $request)
    {
        try {
            if (!$request->has(['name'])) {
                return Response::Error('missing required fields', 422);
            }

            $school = $this->schoolClassService->createClass($request->validated());

            $message = "A new {$school->name} in our Scchool created successfully";
            return Response::Success($school, $message, 201);
            
        } catch (\Exception $e) {
            return Response::Error($e->getMessage(), 409) ;
        } catch (\Throwable $th) {
            $php_errormsg = "An error occurred when creating the school Class";
            return Response::Error($php_errormsg . ': ' . $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $school = $this->schoolClassService->fetchClassItem($request, $id);

            $message = __('"(' . $school['data']->name . ')" details retrieved seccessfully');
            return Response::Success($school, $message);

        }catch (ModelNotFoundException){
            return Response::Error('school not found !!', 404);

        } catch (\Exception $exception){
            $php_errormsg = 'An error occurred while retrieving school details';
            return Response::Error($php_errormsg . ': ' . $exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolClassRequest $request, $id)
    {
        try {
            $validatedData = $request->only(array_filter($request->all()));
            
            if (isset($validatedData['name']) && SchoolClass::where('name', $validatedData['name'])->exists()) {
                return Response::Error('this name alredy exists already', 409);
            }
            $class = $this->schoolClassService->updateClass($validatedData, $id);

            $message = "class updated successfully";
            return Response::Success($class, $message, 200);

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
            $school = $this->schoolClassService->deleteClass($id);
            $message = "$school->name School Class deleted successfully";
            return Response::Success(null, $message, 200);

        } catch (ModelNotFoundException $e) {
            Log::error("Class not found: {$id}");
            return Response::Error('Class not found', 404);

        } catch (\Throwable $th) {
            Log::error('Failed to delete school Class: ' . $th->getMessage());
            return Response::Error('An error occurred. Please try again: ' . $th->getMessage(), 500);
        }
    }
}