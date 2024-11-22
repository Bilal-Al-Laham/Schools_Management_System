<?php

namespace App\Exceptions;

use App\Models\Assignment;
use App\Models\Subject;
use App\Models\User;
use Exception;

class AssignmentsLogicException extends Exception
{
    public static function subjectOrTeacherNotFound(Subject $subject, User $user)
    {
        $teacher = User::where('role', 'teacher')->firstOr();
        if (!$subject || !$teacher) {
            return response()->json(['error' => 'Subject or tescher not found'], 404);
        }
    }


    public static function assignmentNotFound(Assignment $assignment){
        if (!$assignment) {
            throw new Exception("Assignment Not Found", 404);

        }
    }
}
