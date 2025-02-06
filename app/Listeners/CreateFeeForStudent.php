<?php

namespace App\Listeners;

use App\Events\StudentCreated;
use App\Models\Fee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateFeeForStudent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public static function handle(StudentCreated $event)
    {
        Fee::create([
            'student_id' => $event->student->id,
            'total_amount' => 300.00,
            'remaining_amount' => 300.00,
            'first_payment_date' => now()->addMonths(1),
            'final_payment_date' => now()->addMonths(5),
        ]);
    }
}
