<?php

// App\Http\Controllers\Api\ExamController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function index()
{
    $exams = Exam::with('course')->get()->map(function ($exam) {
        return [
            'id' => $exam->id,
            'course_name' => $exam->course->name ?? 'غير معروف',
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'end_time' => $exam->end_time,
            'target_year' => $exam->target_year,
            'type' => $exam->type,
        ];
    });

    return response()->json($exams);
}

public function showByCourse($course_id)
{
    $exams = Exam::with('course')->where('course_id', $course_id)->get()->map(function ($exam) {
        return [
            'id' => $exam->id,
            'course_name' => $exam->course->name ?? 'غير معروف',
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'end_time' => $exam->end_time,
            'target_year' => $exam->target_year,
            'type' => $exam->type,
        ];
    });

    return response()->json($exams);
}

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'course_id' => 'required|exists:courses,id',
        'exam_date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required',
        'type' => 'required|in:midterm,final',
        'target_year' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'خطأ في التحقق من البيانات',
            'errors' => $validator->errors()
        ], 422);
    }

    $exam = Exam::create($validator->validated());

    return response()->json([
        'message' => 'تم إنشاء الامتحان بنجاح',
        'exam' => $exam
    ], 201);
}
    public function update(Request $request, $id)
{
    $exam = Exam::find($id);
    if(!$exam){
        return response()->json(['message' => ' exam not found '], 404);
    }

    $validator = Validator::make($request->all(), [
        'course_id' => 'sometimes|exists:courses,id',
        'exam_date' => 'sometimes|date',
        'start_time' => 'sometimes',
        'end_time' => 'sometimes',
        'type' => 'sometimes|in:midterm,final',
        'target_year' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'خطأ في التحقق من البيانات',
            'errors' => $validator->errors()
        ], 422);
    }

    $exam->update($validator->validated());

    return response()->json([
        'message' => 'تم تحديث الامتحان بنجاح',
        'exam' => $exam
    ]);
}

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        return response()->json(['message' => 'تم حذف الامتحان بنجاح']);
    }
}
