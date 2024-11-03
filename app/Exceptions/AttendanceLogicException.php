<?php

namespace App\Exceptions;

use Exception;

class AttendanceLogicException extends Exception
{
    public function notFoundAttendance(){
        return response()->json(['error' => 'Attendance record not found'], 404);
    }
}
