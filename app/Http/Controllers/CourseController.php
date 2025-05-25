<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(){
        $courses=Course::all();
        return response()->json(['message'=>'all courses successfully','courses'=>$courses],200);
    }

    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|string',
            'department'=>'required|string',
            'year'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $course=Course::create($validator->validated());
        return response()->json(['message'=>'add courses successfully','data'=>$course],201);

    }

    public function update(Request $request,$id){
        $course=Course::find($id);
        if(!$course){
            return response()->json(['message' => ' course not found '], 404);
        }
        $validator=Validator::make($request->all(),[
            'name'=>'sometimes|required|string',
            'department'=>'sometimes|required|string',
            'year'=>'sometimes|required|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $course->update($validator->validated());
        return response()->json(['message'=>'update successfully','data'=>$course]);
    }

    public function destroy($id){
       $course=Course::find($id);
        if(!$course){
            return response()->json(['message' => ' course not found '], 404);
        }
        $course->delete();
        return response()->json('delete course successfully');
    }

}
