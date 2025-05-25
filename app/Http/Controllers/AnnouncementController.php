<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Validator;


class AnnouncementController extends Controller
{
public function index()
{
    $announcements = Announcement::with('user:id,name')
        ->latest()
        ->get()
        ->map(function ($announcement) {
            return [
                'id' => $announcement->id,
                'user_name' => $announcement->user->name ?? 'غير معروف',
                'title' => $announcement->title,
                'content' => $announcement->content,
                'attachment' => $announcement->attachment,
            ];
        });

    return response()->json($announcements);
}


    public function store(Request $request)
    {
        // التحقق من الدور
        if (auth()->user()->role === 'student') {
            return response()->json(['message' => 'الطلاب لا يمكنهم إنشاء الإعلانات'], 403);
        }


        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        $announcement = Announcement::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'attachment' => $path,
        ]);

        return response()->json($announcement);
    }

    
    public function update(Request $request, $id)
    {
    $announcement = Announcement::find($id);

    if (!$announcement) {
        return response()->json(['message' => 'الإعلان غير موجود'], 404);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'sometimes|required|string',
        'content' => 'sometimes|required|string',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120', // دعم المرفقات
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = $validator->validated();

    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('announcements', 'public');
        $data['attachment'] = $path;
    }

    $announcement->update($data);

    return response()->json([
        'message' => 'تم التحديث بنجاح',
        'announcement' => $announcement
    ]);
    }

    public function destroy(Request $request, $id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json(['message' => 'الإعلان غير موجود'], 404);
        }

        if ($request->user()->id !== $announcement->user_id) {
            return response()->json(['message' => 'ليس لديك صلاحية الحذف'], 403);
        }

        $announcement->delete();

        return response()->json(['message' => 'تم حذف الإعلان بنجاح']);
    }



}
