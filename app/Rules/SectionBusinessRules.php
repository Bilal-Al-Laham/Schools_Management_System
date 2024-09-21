<?php

namespace App\Rules;

use App\Exceptions\BusinessLogicException;
use App\Models\SchoolClass;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SectionBusinessRules implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public static function checkMinimumStudents(SchoolClass $schoolClass){
        $minimumStudents = 5;
        if ($schoolClass->students()->count() < $minimumStudents) {
            throw new BusinessLogicException('A section cannot be created for a class with less than ' . $minimumStudents . ' students.');
        }
    }

    public static function checkTeacherAssignment(SchoolClass $schoolClass)
    {
        if (!$schoolClass->teachers()->exists()) {
            throw new BusinessLogicException('No teacher assigned to this school class.');
        }
    }
}
