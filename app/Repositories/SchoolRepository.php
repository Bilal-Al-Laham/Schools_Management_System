<?php

namespace App\Repositories;

use App\Models\School;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

interface SchoolRepositoryInterface
{
    public function getSchools ($request);
    public function getSchoolItem ($request, $id);
    public function createSchool(array $data);
    public function editSchool(array $data, $id);
    public function deleteSchool($id);
}

class SchoolRepository implements SchoolRepositoryInterface
{
    public function getSchools($request) {
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

            return $school;
    }

    public function getSchoolItem($request, $id) {
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
        return $response;
    }

    public function createSchool(array $data) {
        try {
            return School::create($data);
        } catch (QueryException $e) {
            throw new Exception('database query faild: ' . $e->getMessage());
        }
    }
    
    public function editSchool(array $data, $id)
    {
        try {
            return School::findOrFail($id)->update($data);
        } catch (QueryException $e) {
            throw new Exception('database query faild: ' . $e->getMessage());
        }
    }

    public function deleteSchool($id) {
        try {
            return School::findOrFail($id)->delete();
        } catch (QueryException $e) {
            throw new Exception('An error occurred while deleting the school. Please try again.');
        }
    }
}