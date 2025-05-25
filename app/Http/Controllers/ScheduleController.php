<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
public function getTheorySchedulesByYear($year)
{
    $schedules = Schedule::with(['course', 'classroom', 'teacher.user'])
        ->where('type', 'theory')
        ->where('year', $year)
        ->get()
        ->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'course_name' => $schedule->course->name ?? 'غير معروف',
                'classroom_name' => $schedule->classroom->name ?? 'غير معروف',
                'teacher_name' => $schedule->teacher->user->name ?? 'غير معروف',
                'day' => $schedule->day,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'year' => $schedule->year,
            ];
        });

    return response()->json($schedules);
}


public function getLabSchedulesByGroup($group)
{
    $schedules = Schedule::with(['course', 'classroom', 'teacher'])
        ->where('type', 'lab')
        ->where('group', $group)
        ->get()
        ->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'course_name' => $schedule->course->name ?? 'غير معروف',
                'classroom_name' => $schedule->classroom->name ?? 'غير معروف',
                'teacher_name' => $schedule->teacher->user->name ?? 'غير معروف',
                'day' => $schedule->day,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'year' => $schedule->year,
                'group' => $schedule->group,
            ];
        });

    return response()->json($schedules);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'type' => 'required|in:theory,lab',
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'year' => 'nullable|string',
            'group' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule = Schedule::create($validator->validated());
        return response()->json(['message' => 'تمت الإضافة بنجاح', 'schedule' => $schedule]);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'الجدول غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_id' => 'sometimes|exists:courses,id',
            'classroom_id' => 'sometimes|exists:classrooms,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'type' => 'sometimes|in:theory,lab',
            'day' => 'sometimes|string',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'year' => 'nullable|string',
            'group' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedule->update($validator->validated());
        return response()->json(['message' => 'تم التحديث بنجاح', 'schedule' => $schedule]);
    }

    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['message' => 'الجدول غير موجود'], 404);
        }

        $schedule->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
