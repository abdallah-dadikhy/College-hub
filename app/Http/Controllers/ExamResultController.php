<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ExamResultController extends Controller
{
   public function index()
{
    $results = ExamResult::with(['exam.course', 'student'])->get()->map(function ($result) {
        return [
            'id' => $result->id,
            'student_name' => $result->student->user->name ?? 'غير معروف',
            'course_name' => $result->exam->course->name ?? 'غير معروف',
            'score' => $result->score,
            'year' => $result->student->year,
        ];
    });

    return response()->json($results);
}

public function show($id)
{
    $result = ExamResult::with(['exam.course', 'student'])->findOrFail($id);

    return response()->json([
        'id' => $result->id,
        'student_name' => $result->student->user->name ?? 'غير معروف',
        'course_name' => $result->exam->course->name ?? 'غير معروف',
        'score' => $result->score,
        'year' => $result->student->year,
    ]);
}

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'exam_id' => 'required|exists:exams,id',
        'student_id' => 'required|exists:students,id',
        'score' => 'nullable|numeric|min:0|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'خطأ في التحقق من البيانات',
            'errors' => $validator->errors(),
        ], 422);
    }

    $result = ExamResult::create($validator->validated());

    return response()->json([
        'message' => 'تم حفظ النتيجة بنجاح',
        'result' => $result,
    ], 201);
}

 public function update(Request $request, $id)
{
    $result = ExamResult::find($id);

    if (!$result) {
        return response()->json([
            'message' => 'لم يتم العثور على النتيجة المطلوبة'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'exam_id' => 'sometimes|exists:exams,id',
        'student_id' => 'sometimes|exists:students,id',
        'score' => 'nullable|numeric|min:0|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'خطأ في التحقق من البيانات',
            'errors' => $validator->errors(),
        ], 422);
    }

    $result->update($validator->validated());

    return response()->json([
        'message' => 'تم تحديث النتيجة بنجاح',
        'result' => $result,
    ]);
}


    public function destroy($id)
    {
        $result = ExamResult::findOrFail($id);
        $result->delete();
        return response()->json(['message' => 'تم حذف النتيجة بنجاح']);
    }

public function resultsByExam($exam_id)
{
    $results = ExamResult::with('student')
        ->where('exam_id', $exam_id)
        ->get()
        ->map(function ($result) {
            return [
                'student_name' => $result->student->user->name ?? 'غير معروف',
                'score' => $result->score,
            ];
        });

    return response()->json($results);
}


public function resultsByStudent($student_id)
{
    $results = ExamResult::with('exam.course')
        ->where('student_id', $student_id)
        ->get()
        ->map(function ($result) {
            return [
                'course_name' => $result->exam->course->name ?? 'غير معروف',
                'target_year' => $result->exam->target_year,
                'score' => $result->score,
            ];
        });

    return response()->json($results);
}

}
