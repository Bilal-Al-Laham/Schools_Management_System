<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AttendanceLogicException;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Responses\Response;
use App\Models\User;
use App\Repositories\AttendanceRepository;
use App\Repositories\AttendanceRepositoryInterface;
use App\Services\AttendanceService;
use App\Services\AttendanceServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected AttendanceService $attendanceService;
    protected AttendanceRepository $attendanceRepository;


    public function __construct(AttendanceServiceInterface $attendanceServiceInterface, AttendanceRepositoryInterface $attendanceRepositoryInterface)
    {
        $this->attendanceService = $attendanceServiceInterface;
        $this->attendanceRepository = $attendanceRepositoryInterface;


        $this->middleware('role:admin,teacher')->only('store', 'update', 'destroy');
        $this->middleware('role:admin')->only('destroy');
    }


    public function index()
    {
        $user = auth()->user();
        $attendances = $this->attendanceRepository->getAllAttendances();
        $attendanceRecords = $this->attendanceService->getAttendanceByRole($user);

        return $attendanceRecords
            ? Response::Success($attendances, 200)
            : Response::Error('Unauthorized', 403);
    }


    public function store_admins_and_teachers(StoreAttendanceRequest $request)
    {
        if(!Gate::allows('manage-attendance')){
            return Response::Error( 'This Action is unauthorized', 403);
        }

        $result =  $this->attendanceService->record_attendance_for_admins_and_teachers($request->validated());

        return $result
            ? Response::Success('Attendance recorded for admin and teachers', 201)
            : Response::Error('Failed to record attendance', 500);
    }

    public function store_teachers_students_attendance(StoreAttendanceRequest $request){
        $user = auth()->user();
        if ($user->role === 'student') {
            return Response::Error('Unauthorized', 401);
        }

        $result = $this->attendanceService->record_attendance_for_students($request->validated());

        return $result
        ? Response::Success('Attendance recorded for student', 201)
        : Response::Error('Failed to record attendance', 500);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();
        $attendance = $this->attendanceRepository->findAttendance($id);
        if (!$attendance || Gate::allows('view', $attendance)) {
            throw new AttendanceLogicException();
        }

        return $attendance
        ? Response::Success($attendance, 'Attendance details retrieved successfully')
        : Response::Error('Unauthorized access or attendance not found', 403);
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
    public function update(UpdateAttendanceRequest $request, $id)
    {
        $user = auth()->user();
        $attendance = $this->attendanceRepository->findAttendance($id); // تأكد هنا

        if (!$attendance) {
            return Response::Error('Attendance record not found', 404);
        }

        $result = $this->attendanceRepository->updateAttendance($attendance, $request->validated());

        return $result
            ? Response::Success([
                'user' => $attendance,
                'data' => $result, // يمكن أن تكون النتيجة هنا كائن Attendance
            ], 'Attendance updated successfully', 200)
            : Response::Error('Failed to update attendance or unauthorized', 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Auth::user();
        $attendance = $this->attendanceRepository->findAttendance($id);
        if (!$attendance) {
            throw new AttendanceLogicException('Logic error in attendance processing.');
        }
        $daleted = $this->attendanceService->deleteAttendanceRecord($id);

        return $daleted
            ? Response::Success('Attendance record deleted successfully', 200)
            : Response::Error('Failed to delete attendance', 500);
    }
}
