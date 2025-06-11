<?php

// app/Http/Controllers/Api/ExamHallAssignmentController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamHallAssignment;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Exam;
class ExamHallAssignmentController extends Controller
{
public function distribute(Request $request)
{
    $request->validate([
        'exam_id' => 'required|exists:exams,id',
    ]);
    
    $exam = Exam::find($request->exam_id);
        if(!$exam){
        return response()->json(['message' => ' exam not found '], 404);
    }
    $targetYear = $exam->target_year;

    // جلب معلومات الامتحانات التي بنفس التاريخ والوقت ولكن لسنة مختلفة
    $otherExamIds = Exam::where('exam_date', $exam->exam_date)
                        ->where('start_time', $exam->start_time)
                        ->where('id', '!=', $exam->id)
                        ->pluck('id');

    // حذف التوزيع القديم لنفس هذا الامتحان فقط
    ExamHallAssignment::where('exam_id', $exam->id)->delete();

    // جلب الطلاب المستهدفين فقط
    $students = Student::where('year', $targetYear)->get();
    if ($students->isEmpty()) {
        return response()->json([
            'message' => 'لا يوجد طلاب في السنة الدراسية المستهدفة',
            'target_year' => $targetYear,
            'students_count' => 0
        ], 404);
    }

    $classrooms = Classroom::all();
    if ($classrooms->isEmpty()) {
        return response()->json(['message' => 'لا توجد قاعات متاحة'], 400);
    }

    $maxSeatsPerClassroom = 30;

    // جلب آخر توزيع حصل في نفس التاريخ والوقت لأي سنة دراسية
    $lastAssignments = ExamHallAssignment::whereIn('exam_id', $otherExamIds)
        ->orderBy('classroom_id')
        ->orderByDesc('seat_number')
        ->get();

    $lastSeatMap = [];
    foreach ($lastAssignments as $assignment) {
        $key = $assignment->classroom_id;
        if (!isset($lastSeatMap[$key])) {
            $lastSeatMap[$key] = $assignment->seat_number;
        }
    }

    $distributionResults = [];

    // إعداد المؤشرات للبداية
    $classroomIndex = 0;
    $seatNumber = isset($classrooms[$classroomIndex]) && isset($lastSeatMap[$classrooms[$classroomIndex]->id]) 
                    ? $lastSeatMap[$classrooms[$classroomIndex]->id] + 1 
                    : 1;

    foreach ($students as $student) {
        $placed = false;
        $attempts = 0;

        while (!$placed && $attempts < $classrooms->count()) {
            $classroom = $classrooms[$classroomIndex];

            // إذا تجاوزنا السعة ننتقل للقاعة التالية
            if ($seatNumber > $maxSeatsPerClassroom) {
                $classroomIndex = ($classroomIndex + 1) % $classrooms->count(); // تدوير القاعات
                $seatNumber = isset($lastSeatMap[$classrooms[$classroomIndex]->id]) 
                              ? $lastSeatMap[$classrooms[$classroomIndex]->id] + 1 
                              : 1;
                $attempts++;
                continue;
            }

            ExamHallAssignment::create([
                'exam_id' => $exam->id,
                'classroom_id' => $classroom->id,
                'student_id' => $student->id,
                'seat_number' => $seatNumber,
            ]);

            $distributionResults[] = [
                'student_name' => $student->user->name ?? 'غير معروف',
                'classroom_name' => $classroom->name,
                'seat_number' => $seatNumber,
            ];

            $seatNumber++;
            $placed = true;
        }

        if (!$placed) {
            return response()->json([
                'message' => 'عدد الطلاب أكبر من القدرة الاستيعابية الكلية للقاعات المتاحة.',
            ], 400);
        }
    }

    return response()->json([
        'message' => 'تم توزيع الطلاب على القاعات بنجاح',
        'distribution' => $distributionResults
    ]);
}



   public function show($examId)
{
    // جلب التوزيع مع بيانات الطلاب والقاعات وعلاقاتهم
    $assignments = ExamHallAssignment::with(['student.user', 'classroom'])
        ->where('exam_id', $examId)
        ->orderBy('classroom_id')
        ->orderBy('seat_number')
        ->get();

    // تجهيز البيانات المطلوبة فقط
    $data = $assignments->map(function ($assignment) {
        return [
            'student_name' => $assignment->student->user->name ?? 'غير معروف',
            'classroom_name' => $assignment->classroom->name ?? 'غير معروف',
            'seat_number' => $assignment->seat_number,
        ];
    });

    return response()->json($data);
}

}

