<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $assignments = Assignment::orderBy('id', 'desc')->take(5)->get();
        // $assignments = Assignment::where('due_date', '', 2)->get();
        // dd($assignments);



        // $assignments =  Assignment::chunk(9, function ($assignments){
        //     foreach ($assignments as $assignment){
        //         echo $assignment->section . '<br>';
        //     }
        // });

        $assignments = Assignment::orderBy('created_at')->get();
        // dd($assignments);
        return view('index', [
            'assignments' => $assignments
        ]);
        // $assignments = DB::table('assignments')->get();
        // $assignments = DB::table('assignments')->find(1);


        // return view('index')->with('assignments', $assignments);
        // return view('index', compact('assignments'));
        // return view('index', [
        //     'assignments' => DB::table('assignments')->get()
        // ]);
        return view('index');
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
    public function store(StoreAssignmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}
