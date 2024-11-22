<?php

namespace App\Actions;

use App\Exceptions\AssignmentsLogicException;
use App\Models\Assignment;
use App\Models\section;
use App\Models\Subject;
use App\Models\User;

class CloseAssignmentAction
{
    public function excute(Assignment $assignment){
        if ($assignment->due_date < now()) {
            $assignment->status = 'closed';
            $assignment->save();
            return $assignment;
        }

        throw new \Exception('Cannot close the assignment before today.');
    }

}
