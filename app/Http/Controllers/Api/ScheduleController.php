<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Http\Requests\Schedules\StoreScheduleRequest;
use App\Http\Requests\Schedules\UpdateScheduleRequest;
use App\Http\Responses\Response;
use App\Models\Examention;
use App\Models\section;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Objects\Payments\OrderInfo;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Schedule::query()
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();
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
    public function store($sectionId)
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        $startTime = Carbon::createFromTime(8, 0);
        $periodDuration = 45;
        $breakDuration = 15;
        $periodsPerDay = 6;

        $section = Section::with('school_class.subjects.teacher')->findOrFail($sectionId);
        $subjects = $section->school_class->subjects;

        if ($subjects->isEmpty()) {
            return response()->json(['error' => 'No subjects found for this section.'], 400);
        }

        $teacherAvailability = [];

        foreach ($days as $day) {
            $currentTime = clone $startTime;

            for ($i = 1; $i <= $periodsPerDay; $i++) {
                foreach ($subjects as $subject) {
                    $teacherId = $subject->teacher_id;

                    if (!isset($teacherAvailability[$teacherId][$day]) ||
                        !in_array($currentTime->toTimeString(), $teacherAvailability[$teacherId][$day])) {

                        // إنشاء الحصة في جدول schedules
                        $scheduleEntry = Schedule::create([
                            'name' => "Period $i",
                            'section_id' => $sectionId,
                            'type' => 'weekly',
                            'day_of_week' => $day,
                            'subject_id' => $subject['id'],
                            'start_time' => $currentTime->toTimeString(),
                            'end_time' => $currentTime->copy()->addMinutes($periodDuration)->toTimeString(),
                        ]);

                        // ربط الحصة بالمادة في جدول الكسر
                        DB::table('schedule_subject')->insert([
                            'schedule_id' => $scheduleEntry->id, // معرف الحصة
                            'subject_id' => $subject->id,        // معرف المادة
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // تحديث توفر المدرس
                        $teacherAvailability[$teacherId][$day][] = $currentTime->toTimeString();

                        $currentTime->addMinutes($periodDuration);

                        if ($i % 2 == 0 && $i < $periodsPerDay) {
                            $currentTime->addMinutes($breakDuration);
                        }

                        break;
                    }
                }
            }
        }

        return response()->json(['message' => 'Weekly schedule generated successfully.', 'schedule' => $scheduleEntry], 201);
    }



    public function createExamSchedule($sectionId)
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        $startTime = Carbon::createFromTime(8, 0); // وقت بدء الامتحانات
        $examDuration = 120; // مدة الامتحان بالدقائق (2 ساعة)
        $breakDuration = 30; // مدة الاستراحة بين الامتحانات
        $maxExamsPerDay = 2; // عدد الامتحانات في اليوم

        // جلب المواد المرتبطة بالقسم
        $section = Section::with('school_class.subjects')->findOrFail($sectionId);
        $subjects = $section->school_class->subjects;

        // تأكد أن اسم العمود في قاعدة البيانات مطابق.
        $subjectQueue = $subjects->toArray();


        if ($subjects->isEmpty()) {
            return response()->json(['error' => 'No subjects found for this section.'], 400);
        }

        $examSchedule = [];
        $dayIndex = 0;

        while (!empty($subjectQueue)) {
            $currentTime = clone $startTime;

            for ($i = 1; $i <= $maxExamsPerDay; $i++) {
                if (empty($subjectQueue)) break;

                $subject = array_shift($subjectQueue);

                // إنشاء سجل جدول الامتحانات في جدول schedules
                $examEntry = Schedule::create([
                    'name' => "Exam for {$subject['name']}",
                    'section_id' => $sectionId,
                    'subject_id' => $subject['id'],
                    'type' => 'exam',
                    'date' => now()->addDays($dayIndex)->toDateString(), // تاريخ الامتحان
                    'start_time' => $currentTime->toTimeString(),
                    'end_time' => $currentTime->copy()->addMinutes($examDuration)->toTimeString(),
                    'notes' => "Exam scheduled for {$subject['name']} on {$days[$dayIndex]}.",
                ]);

                // تخزين الامتحان في الجدول المساعد
                $examSchedule[] = $examEntry;

                // تحديث الوقت للفترة التالية
                $currentTime->addMinutes($examDuration + $breakDuration);
            }

            // الانتقال إلى اليوم التالي
            $dayIndex = ($dayIndex + 1) % count($days);
        }

        return response()->json([
            'message' => 'Exam schedule created successfully.',
            'schedule' => $examSchedule,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
