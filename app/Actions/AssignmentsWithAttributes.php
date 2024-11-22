<?php

namespace App\Actions;

use App\Exceptions\AssignmentsLogicException;
use App\Models\Assignment;
use App\Models\section;
use App\Models\Subject;
use App\Models\User;

class AssignmentsWithAttributes{

    public static function getAssignmentAttributes(Assignment $assignment){
            $subject = Subject::where('name', $assignment['subject_name'])->first();

            $section = $assignment['section_name']
            ? section::where('name', $assignment['section_name'])->first()
            : null;

            $teacher = User::where('name', $assignment['teacher_name'])->first();

            if ($assignment['section_name'] && !$section) {
                return response()->json(['error' => 'Section not found'], 404);
            }

            if (!$subject || !$teacher) {
                throw new AssignmentsLogicException();
            }

        }
    }
