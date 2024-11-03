<?php

namespace App\Services;

use App\Http\Responses\Response;
use App\Models\Attendance;
use App\Models\User;
use App\Repositories\AttendanceRepositoryInterface;
use App\Rules\AttendanceBusinessRule;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Auth;

interface AttendanceServiceInterface
{
    public function getAttendanceByRole(User $user);
    public function record_attendance_for_admins_and_teachers( $data);
    public function record_attendance_for_students( $data);
    public function getAttendanceForStudent($id, User $user);
    public function updateAttendanceRecord($id, $data, Attendance $attendance);
    public function deleteAttendanceRecord($id);
}

class AttendanceService implements AttendanceServiceInterface
{
    private $attendanceRepository;
    private $rules;

    public function __construct(AttendanceRepositoryInterface $attendanceRepositoryInterface, AttendanceBusinessRule $attendanceBusinessRule)
    {
        $this->attendanceRepository = $attendanceRepositoryInterface;
        $this->rules = $attendanceBusinessRule;
    }

    public function getAttendanceByRole(User $user){
        switch ($user->role) {
            case 'admin':
                return Attendance::with('user')->whereDay('date', now()->day)->get();
                    break;
            case 'teacher':
                return Attendance::with('user')->where(function ($teacherQuery) use ($user){
                    $teacherQuery->where('user_id', $user->id)
                    ->orWhereHas('user', function ($studentQuery) use ($user) {
                        $studentQuery->where('role', 'student')
                        ->where('school_class_id', $user->school_class_id);
                    });
                })->whereDay('date', now()->day);
                    break;
            case 'student':
                return Attendance::with('user')
                ->where('user_id', $user->id)
                ->whereDay('date', now()->day)
                ->get();
                    break;
            default:
                return null;
        }
        // $attendanceRecords = $this->attendanceService->
        // $attendanceRecords = match($user->role) {
        //     User::ROLE_ADMIN => $this->index_for_admin_attendance(),
        //     User::ROLE_TEACHER => $this->index_for_taecher_attendance($user),
        //     User::ROLE_STUDENT => $this->index_for_student_attendance($user),
        //     default => null
        // };
    }


    public function record_attendance_for_admins_and_teachers( $data){

        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $attendanceAdmin = Attendance::create([
            'user_id' => $user->id,
            'role' => 'admin',
            'date' => now()->toDateString(),
            'status' => 'present',
            'notes' => null
        ]);

        $teachers = User::where('role', 'teacher')->get();
        foreach ($teachers as $teacher) {
            $attendanceTeacher = Attendance::create([
                'user_id' => $teacher->id,
                'role' => 'teacher',
                'date' => $data['date'] ?? now()->toDateString(),
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null
            ]);
        }
        return response()->json([
            'message' => 'Attendance recorded for admin and teachers',
            "date today" => "today is" . ($data['date'] ?? now()->toDateString()),
            "admin status today" => $attendanceAdmin,
            "$teacher->name status today" => $attendanceTeacher

        ]);
    }

    public function record_attendance_for_students($data)
    {

        $studentId = is_array($data) ? $data['user_id'] :$data->input('user_id');
        // $date = is_array($data) ? ($data['date'] ?? now()->toDateString()) : $data->input('date', now()->toDateString());

        $student = User::find($studentId);

        if (!$student || $student->role !== 'student') {
            return Response::Error('Invalid user ID or the user is not a student', 400);
        }

        foreach ($student as $std) {
            $attendance = Attendance::create([
                'user_id' => $student->id,
                'role' => 'student',
                'date' => $data['date'] ?? now()->toDateString(),
                'status' => $data['status'] ?? 'present',
                'notes' => null
            ]);
            $attendanceRecords[] = $attendance;
        }

            return $attendanceRecords;
    }

    public function getAttendanceForStudent($id, User $user)
    {
        $attendance = Attendance::with('user')->find($id);

        if ($user->role === 'admin') {
            return $attendance;

        } elseif ($user->role === 'teacher' && in_array($attendance->role, ['student', 'teacher'])) {
                return $attendance;

        } elseif ($user->role === 'student' && $attendance->user_id === $user->id && $attendance->role === 'student') {
                return $attendance;

        } else {
            return Response::Error('Unauthorized', 402);
        }
    }

    public function updateAttendanceRecord($id, $data, Attendance $attendance)
    {
        if (!$attendance) {
            return Response::Error('Attendance record not found', 404);
        }
        if ($attendance->role === 'admin' || ($attendance->role === 'teacher' && $attendance->role === 'student')) {
            $attendance->update($data);
            return $attendance;
        }

    }

    public function deleteAttendanceRecord($id)
    {
        $user = Auth::user();
        if ($user->roles !== 'admin') {
            return Response::Error('Unauthorized', 403);
        }
        return $this->attendanceRepository->deleteAttendance($id);
    }
}
