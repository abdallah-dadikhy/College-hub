<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeProfile;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use App\Models\Student;
use App\Models\User;

class ProfileController extends Controller
{
    // ======================= الموظفين ===========================

    public function getAllStaff()
    {
        $staffs = EmployeeProfile::all();
        return response()->json($staffs);
    }

    public function getStaff($id)
    {
        $staff = EmployeeProfile::find($id);
        if (!$staff) {
            return response()->json(['message' => 'الموظف غير موجود'], 404);
        }
        return response()->json($staff);
    }

    public function createStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'mother_name' => 'required|string',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string',
            'academic_degree' => 'required|string',
            'department' => 'required|string',
            'employment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $staff = EmployeeProfile::create($validator->validated());
        return response()->json($staff, 201);
    }

    public function updateStaff(Request $request, $id)
    {
        $staff = EmployeeProfile::find($id);
        if (!$staff) {
            return response()->json(['message' => 'الموظف غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|string',
            'mother_name' => 'sometimes|required|string',
            'birth_date' => 'sometimes|required|date',
            'birth_place' => 'sometimes|required|string',
            'degree' => 'sometimes|required|string',
            'department' => 'sometimes|required|string',
            'hire_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $staff->update($validator->validated());
        return response()->json($staff);
    }

    public function deleteStaff($id)
    {
        $staff = EmployeeProfile::find($id);
        if (!$staff) {
            return response()->json(['message' => 'الموظف غير موجود'], 404);
        }
        $staff->delete();
        return response()->json(['message' => 'تم حذف الموظف']);
    }

    // ======================= الدكاترة ===========================

    public function getAllTeachers()
    {
        $teachers = TeacherProfile::all();
        return response()->json($teachers);
    }

    public function getTeacher($id)
    {
        $teacher = TeacherProfile::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'الدكتور غير موجود'], 404);
        }
        return response()->json($teacher);
    }

    public function createTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'mother_name' => 'required|string',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string',
            'academic_degree' => 'required|string',
            'degree_source' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teacher = TeacherProfile::create($validator->validated());
        return response()->json($teacher, 201);
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = TeacherProfile::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'الدكتور غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|string',
            'mother_name' => 'sometimes|required|string',
            'birth_date' => 'sometimes|required|date',
            'birth_place' => 'sometimes|required|string',
            'academic_degree' => 'sometimes|required|string',
            'degree_source' => 'sometimes|required|string',
            'department' => 'sometimes|required|string',
            'position' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teacher->update($validator->validated());
        return response()->json($teacher);
    }

    public function deleteTeacher($id)
    {
        $teacher = TeacherProfile::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'الدكتور غير موجود'], 404);
        }
        $teacher->delete();
        return response()->json(['message' => 'تم حذف الدكتور']);
    }

    // ======================= الطلاب ===========================

    public function getAllStudents()
    {
        $students = StudentProfile::all();
        return response()->json($students);
    }

    public function getStudent($id)
    {
        $student = StudentProfile::find($id);
        if (!$student) {
            return response()->json(['message' => 'الطالب غير موجود'], 404);
        }
        return response()->json($student);
    }

        public function createStudent(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string',
                'university_id' => 'required|string',
                'mother_name' => 'required|string',
                'birth_date' => 'required|date',
                'birth_place' => 'required|string',
                'department' => 'required|string',
                'high_school_gpa' => 'required|numeric',
                'photo_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);
            $exists = StudentProfile::where('university_id', $request->university_id)->exists();

            if ($exists) {
                return response()->json(['message' => 'الملف الشخصي موجود مسبقاً لهذا الرقم الجامعي'], 409);
            }
            $studentExists = Student::where('university_id', $request->university_id)->exists();

            if (!$studentExists) {
                return response()->json(['message' => 'الطالب غير موجود في قاعدة البيانات، الرجاء إضافته أولاً.'], 422);
            }


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $validator->validated();

            if ($request->hasFile('photo_path')) {
                $path = $request->file('photo_path')->store('students_profiles', 'public');
                $data['photo_path'] = $path;
            }

            $student = StudentProfile::create($data);
            return response()->json($student, 201);
        }

    public function updateStudent(Request $request, $id)
    {
        $student = StudentProfile::find($id);
        if (!$student) {
            return response()->json(['message' => 'الطالب غير موجود'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|string',
            'university_id' => 'sometimes|required|string',
            'mother_name' => 'sometimes|required|string',
            'birth_date' => 'sometimes|required|date',
            'birth_place' => 'sometimes|required|string',
            'department' => 'sometimes|required|string',
            'high_school_gpa' => 'sometimes|required|numeric',
            'profile_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('students_profiles', 'public');
            $data['profile_image'] = $path;
        }

        $student->update($data);
        return response()->json($student);
    }

    public function deleteStudent($id)
    {
        $student = StudentProfile::find($id);
        if (!$student) {
            return response()->json(['message' => 'الطالب غير موجود'], 404);
        }
        $student->delete();
        return response()->json(['message' => 'تم حذف الطالب']);
    }

    public function getStudentProfilePhoto(Request $request)
{
    $user = $request->user(); // المستخدم الحالي

    // التأكد أنه طالب
    if ($user->role !== 'student') {
        return response()->json(['message' => 'هذا المستخدم ليس طالبًا'], 403);
    }

    $student = $user->student;
    if (!$student) {
        return response()->json(['message' => 'الطالب غير موجود'], 404);
    }

    $profile = $student->profile;
    if (!$profile || !$profile->photo_path) {
        return response()->json(['message' => 'الصورة غير موجودة'], 404);
    }

    return response()->json([
        'photo_path' => asset('storage/' . $profile->photo_path)
    ]);
}
}
