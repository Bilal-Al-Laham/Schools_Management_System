<?php

namespace App\Repositories;

use App\Models\Examention;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;

use function PHPUnit\Framework\returnSelf;

interface ExamentionRepositoryInterface
{
    public function getExametions();
    public function addExametion(array $data);
    public function showExametin($id);
    public function updateExamention($data, $id);
}
class ExamentionRepository implements ExamentionRepositoryInterface
{
    public function getExametions()
    {
        $exametions = Examention::query()
            ->with(['school_class', 'subjects.teacher', 'examResult.student'])
            ->orderBy('created_at', 'asc')
            ->paginate(5);

        return $exametions;
    }

    public function showExametin($id)
    {
        $exametion = Examention::query()
            ->where('id', $id)
            // ->with(['subjects', 'school_class'])
            ->with(relations: ['subjects.teacher', 'school_class'])
            ->first();

        return $exametion;
    }

    public function addExametion(array $data)
    {
        try {
            $exametion = Examention::query()->create($data);
            return $exametion;
        } catch (QueryException $e) {
            throw new Exception('database query faild: ' . $e->getMessage());
        }
    }

    public function updateExamention($data, $id)
    {
        try {
            $exametion = Examention::query()->find($id);

            $exametion->update([
                'name' => $data['name'] ? $data['name'] : $exametion->name,
                'subject_id' => $data['subject_id'] ? $data['subject_id'] : $exametion->subject_id,
                'school_class_id' => $data['school_class_id'] ? $data['school_class_id'] : $exametion->school_class_id,
                'exam_date' => $data['exam_date'] ? $data['exam_date'] : $exametion->exam_date,
                'start_time' => $data['start_time'] ? $data['start_time'] : $exametion->start_time,
                'end_time' => $data['end_time'] ? $data['end_time'] : $exametion->end_time,
                'type' => $data['type'] ? $data['type'] : $exametion->type,
            ]);
            $exametion->save();

            return $exametion;
        } catch (QueryException $e) {
            throw new Exception('database query faild: ' . $e->getMessage());
        }
    }
}
