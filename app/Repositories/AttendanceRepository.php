<?php

namespace App\Repositories;

use App\Models\Attendance;

interface AttendanceRepositoryInterface
{
    public function getAttendanceByDate($date);
    public function createAttendance($data);
    public function getAllAttendances();
    public function updateAttendance(Attendance $attendance, $data);
    public function deleteAttendance($id);
    public function findAttendance($id);

}
class AttendanceRepository implements AttendanceRepositoryInterface
{

    public function getAllAttendances()
    {
        return Attendance::with('user')->get();
    }

    public function getAttendanceByDate($date)
    {
        return Attendance::with('user')->whereDate('date', $date)->get();
    }

    public function createAttendance($data)
    {
        return Attendance::create($data);
    }

    public function findAttendance($id)
    {
        return Attendance::find($id);
    }

    public function updateAttendance(Attendance $attendance, $data)
    {
        return $attendance->update($data);
    }

    public function deleteAttendance($id)
    {
        $attendance = Attendance::find($id);
        return $attendance ? $attendance->delete() : false;
    }

}
