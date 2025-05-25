<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\Staff;

class AuthController extends Controller
{
    // تسجيل مستخدم جديد
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:student,teacher,admin,staff',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // إنشاء سجل الدور المناسب
        switch ($request->role) {
            case 'student':
                $request->validate([
                    'university_id' => 'required|unique:students',
                    'faculty' => 'required',
                    'year' => 'required',
                ]);
                Student::create([
                    'user_id' => $user->id,
                    'university_id' => $request->university_id,
                    'faculty' => $request->faculty,
                    'department' => $request->department,
                    'year' => $request->year,
                    'section' => $request->section,
                ]);
                break;

            case 'teacher':
                $request->validate([
                    'employee_id' => 'required|unique:teachers',
                    'department' => 'required',
                ]);
                Teacher::create([
                    'user_id' => $user->id,
                    'employee_id' => $request->employee_id,
                    'department' => $request->department,
                    'position' => $request->position,
                ]);
                break;

            case 'admin':
                $request->validate(['position' => 'required']);
                Admin::create([
                    'user_id' => $user->id,
                    'position' => $request->position,
                ]);
                break;

            case 'staff':
                $request->validate(['department' => 'required', 'position' => 'required']);
                Staff::create([
                    'user_id' => $user->id,
                    'department' => $request->department,
                    'position' => $request->position,
                ]);
                break;
        }

        return response()->json(['message' => 'تم إنشاء الحساب بنجاح'], 201);
    }

    // تسجيل دخول
    public function login(Request $request)
    {
        $request->validate(['role' => 'required|in:student,teacher,admin,staff']);

        $user = null;

        if ($request->role == 'student') {
            $request->validate([
                'university_id' => 'required',
                'password' => 'required'
            ]);
            $student = Student::where('university_id', $request->university_id)->first();
            if ($student && Hash::check($request->password, $student->user->password)) {
                $user = $student->user;
            }
        }

        elseif ($request->role == 'teacher') {
            $request->validate([
                'employee_id' => 'required',
                'password' => 'required'
            ]);
            $teacher = Teacher::where('employee_id', $request->employee_id)->first();
            if ($teacher && Hash::check($request->password, $teacher->user->password)) {
                $user = $teacher->user;
            }
        }

        elseif (in_array($request->role, ['admin', 'staff'])) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $user = User::where('email', $request->email)->where('role', $request->role)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
            }
        }

        if (!$user) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }
}

