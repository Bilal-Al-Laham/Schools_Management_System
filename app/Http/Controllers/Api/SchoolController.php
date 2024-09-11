<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Responses\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $availableSortBy = ['name'];
            $sortBy = $request->get('sort_by', 'name');
            
            if (!in_array($sortBy, $availableSortBy) || empty($sortBy)) {
                $sortBy = 'name';
            }
            $sortOrder = in_array($request->get('sort_order'), ['asc', 'desc']) ? $request->get('sort_order') : 'asc';
            $searchTerm = $request->get('search', '');
            $address = $request->get('address');
            
            $query = School::query()
            ->withCount(['users', 'school_classes'])
            ->with(['school_classes', 'library', 'users'])
            ->orderBy($sortBy, $sortOrder)
            ->where('name', 'LIKE', '%' . $searchTerm . '%');

            if (!empty($address)) {
                $query->where('address', 'LIKE', '%' . $address . '%');
            }
            
            $school = $query->paginate(5);

            $message = 'These are all schools sorted by '. $sortBy;
            return Response::Success($school, $message); 
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
        DB::beginTransaction();
        try {
            if (!$request->has(['name', 'address', 'phone_number'])) {
                return Response::Error('missing required fields', 422);
            }
            $school = School::create($request->validated());
            DB::commit();
            $message = "{$school->name} school created successfully";
            return Response::Success($school, $message, 201);

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query faild: ' . $e->getMessage());
            return Response::Error('An error occurred while creating the school. Please try again.' . $e->getMessage(), 409);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Faild to create school: ' . $th->getMessage());
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
            $relations  = $request->get('include', ['school_classes', 'library', 'users']);
            $school = School::query()
            ->withCount(['school_classes', 'users'])
            ->with($relations)
            ->findOrFail($id);


            $response = [
                'data' => $school,
                'links' => [
                    'self' => route('school.show', ['id' => $school->id])
                ],
                'meta' => [
                'requested_at' => now()->toDateTimeString(),
                'retrived_by' => $school->user
                ]
            ];

            $message = __('school "(' . ($school->name) . ')" details retrieved seccessfully');
            return Response::Success($response, $message);

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
        DB::beginTransaction();
        try {
            $school = School::findOrFail($id);

            $validatedData = $request->only(array_filter($request->all()));
            
            if (isset($validatedData['phone_number']) && School::where('phone_number', $validatedData['phone_number'])->where('id', '!', $school->id)->exists()) {
                return Response::Error('this phone number alredy exists for another school', 409);
            }
 // vweke
            $school->update($request->validated());
            DB::commit();

            $message = "{$school->name} shool updated successfully";
            return Response::Success($school, $message, 200);

        } catch (ModelNotFoundException $th) {
            DB::rollBack();
            Log::error('school not found: ' . $th->getMessage());
            return Response::Error('An error occurred while updating the school!!... please try again.' . $th->getMessage());

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query failed: ' . $e->getMessage());
            return Response::Error('An error occurred while updating the school. Please try again.' . $e->getMessage(), 409);
    
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
        DB::beginTransaction();
        try {
            $school = School::findOrFail($id);
            $school->delete();
            DB::commit();

            $message = "{$school->name} school deleted successfully";
            return Response::Success(null, $message, 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error("School not found: {$id}");
            return Response::Error('School not found', 404);
        
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query failed: ' . $e->getMessage());
            return Response::Error('An error occurred while deleting the school. Please try again.', 500);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to delete school: ' . $th->getMessage());
            return Response::Error('An error occurred. Please try again.', 500);
        }
    }
}
