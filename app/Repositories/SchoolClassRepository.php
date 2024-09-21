<?php

namespace App\Repositories;

use App\Models\SchoolClass;
use Exception;
use Illuminate\Database\QueryException;

interface SchoolClassRepositoryInterface
{
    public function getClasses ($request);
    public function getClassItem ($request, $id);
    public function addClass(array $data);
    public function editClass(array $data, $id);
    public function removeClass($id);
}

class SchoolClassRepository implements SchoolClassRepositoryInterface
{
    public function getClasses($request) {
        $availableSortBy = ['name'];
        $sortBy = $request->get('sort_by', 'name');
        
        if (!in_array($sortBy, $availableSortBy) || empty($sortBy)) {
            $sortBy = 'name';
        }
        $sortOrder = in_array($request->get('sort_order'), ['asc', 'desc']) ? $request->get('sort_order') : 'asc';
        $searchTerm = $request->get('search', '');
        
        $query = SchoolClass::query()      
        ->withCount(['sections', 'examentions', 'students'])
        ->with(['sections', 'examentions', 'students'])
        ->orderBy($sortBy, $sortOrder)
        ->where('name', 'LIKE', '%' . $searchTerm . '%');
        
        $class = $query->paginate(5);

        return $class;
    }

    public function getClassItem($request, $id) {
        $relations  = $request->get('include', ['school_classes', 'library', 'users']);
        $class = SchoolClass::query()
        // ->withCount(['sections', 'examentions', 'students'])
        // ->with(relations: ['sections', 'examentions', 'students'])
        ->findOrFail($id);


        $response = [
            'data' => $class,
            'links' => [
                'self' => route('Class.show', ['id' => $class->id])
            ],
            'meta' => [
            'requested_at' => now()->toDateTimeString(),
            'retrived_by' => $class->user
            ]
        ];
        return $response;
    }

    public function addClass(array $data) {
        try {
            return SchoolClass::create($data);
        } catch (QueryException $e) {
            throw new Exception('database query faild: ' . $e->getMessage());
        }
    }
    
    public function editClass(array $data, $id)
    {
        try {
            return SchoolClass::findOrFail($id)->update($data);
        } catch (QueryException $e) {
            throw new Exception('database query faild: ' . $e->getMessage());
        }
    }

    public function removeClass($id) {
        try {
            return SchoolClass::findOrFail($id)->delete();
        } catch (QueryException $e) {
            throw new Exception('An error occurred while deleting the school class. Please try again.');
        }
    }
}