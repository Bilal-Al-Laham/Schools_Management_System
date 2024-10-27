<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Services\AttendanceService;
use App\Services\AttendanceServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected AttendanceService $attendanceService;
    public function __construct(AttendanceServiceInterface $attendanceServiceInterface)
    {
        $this->attendanceService = $attendanceServiceInterface;
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // if (Auth::user()->role !== 'admin') {
        //     abort(403, 'Unauthorized');
        // }
        // $this->authorize('viewAny', Attendance::class);

        // if (Auth::check()) {
        //     $role = Auth::user()->role;
        // } else {
        //     return response()->json(['message' => 'user not logged in'], 401);
        // }
        // $attendances = $this->attendanceService->getAllAttendances();
        $query = Attendance::query();
        if ($request->has('date')) {
            $query->whereDate('date', Carbon::parse($request->input('date')));
        }
        $attendances = $query->with('user')->get();

        return response()->json($attendances, 200);
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
    public function store(StoreAttendanceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
