<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\ExamHallAssignmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

##--------------------------------------------------- Auth module
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('users', [AuthController::class, 'users']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('courses-with-teachers', [CourseController::class, 'allCoursesWithTeachers']);

Route::controller(ExamController::class)->group(function () {
    Route::get('exams','index');
    Route::get('exam/{course_id}','showByCourse');
    Route::post('exams','store');
    Route::put('exams/{id}','update');    
    Route::delete('exams/{id}','destroy'); 
});
    
Route::prefix('exam-results')->group(function () {
    Route::get('/', [ExamResultController::class, 'index']);
    Route::get('/{id}', [ExamResultController::class, 'show']);
    Route::post('/', [ExamResultController::class, 'store']);
    Route::put('/{id}', [ExamResultController::class, 'update']);
    Route::delete('/{id}', [ExamResultController::class, 'destroy']);
    Route::get('/exam/{exam_id}', [ExamResultController::class, 'resultsByExam']);
    Route::get('/student/{student_id}', [ExamResultController::class, 'resultsByStudent']);
});

Route::post('exam-hall-assignments/distribute', [ExamHallAssignmentController::class, 'distribute']);
Route::get('exam-hall-assignments/{examId}', [ExamHallAssignmentController::class, 'show']);

// الموظفين
Route::get('staff', [ProfileController::class, 'getAllStaff']);
Route::get('staff/{id}', [ProfileController::class, 'getStaff']);
Route::post('staff', [ProfileController::class, 'createStaff']);
Route::put('staff/{id}', [ProfileController::class, 'updateStaff']);
Route::delete('staff/{id}', [ProfileController::class, 'deleteStaff']);

// الدكاترة
Route::get('teachers', [ProfileController::class, 'getAllTeachers']);
Route::get('teachers/{id}', [ProfileController::class, 'getTeacher']);
Route::post('teachers', [ProfileController::class, 'createTeacher']);
Route::put('teachers/{id}', [ProfileController::class, 'updateTeacher']);
Route::delete('teachers/{id}', [ProfileController::class, 'deleteTeacher']);

// الطلاب
Route::get('students', [ProfileController::class, 'getAllStudents']);
Route::get('students/{id}', [ProfileController::class, 'getStudent']);
Route::post('students', [ProfileController::class, 'createStudent']);
Route::put('students/{id}', [ProfileController::class, 'updateStudent']); 
Route::delete('students/{id}', [ProfileController::class, 'deleteStudent']);

Route::get('users', [UserController::class, 'getalluser']);


Route::middleware('auth:sanctum')->get('student/photo', [ProfileController::class, 'getStudentProfilePhoto']);

Route::middleware('auth:sanctum')->post('announcements', [AnnouncementController::class, 'store']);
Route::get('/announcements', [AnnouncementController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('announcements/{id}', [AnnouncementController::class, 'destroy']);
});

Route::controller(CourseController::class)->group(function(){
    Route::get('course','index');
    Route::post('course','store');
    Route::put('course/{id}','update');
    Route::delete('course/{id}','destroy');
});

Route::controller(ClassroomController::class)->group(function(){
    Route::get('classroom','index');
    Route::post('classroom','store');
    Route::put('classroom/{id}','update');
    Route::delete('classroom/{id}','destroy');
});

Route::controller(ScheduleController::class)->group(function(){
    Route::get('schedules/theory/{year}','getTheorySchedulesByYear');
    Route::get('schedules/lab/{group}','getLabSchedulesByGroup');
    Route::post('schedules','store');
    Route::put('schedules/{id}','update');
    Route::delete('schedules/{id}','destroy');
});
