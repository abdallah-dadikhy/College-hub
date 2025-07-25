<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Classroom;
class ClassroomController extends Controller
{
    public function index(){
        $classroom=Classroom::all();
        return response()->json(['message'=>'all classrooms  successfully','classroom'=>$classroom],200);
    }

    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|string',
            'type'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $classroom=Classroom::create($validator->validated());
        return response()->json(['message'=>'add classroom successfully','data'=>$classroom],201);

    }

    public function update(Request $request,$id){
        $classroom=Classroom::find($id);
        if(!$classroom){
            return response()->json(['message' => ' classroom not found '], 404);
        }
        $validator=Validator::make($request->all(),[
            'name'=>'sometimes|required|string',
            'type'=>'sometimes|required|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $classroom->update($validator->validated());
        return response()->json(['message'=>'update successfully','data'=>$classroom]);
    }

    public function destroy($id){
       $classroom=Classroom::find($id);
        if(!$classroom){
            return response()->json(['message' => ' classroom not found '], 404);
        }
        $classroom->delete();
        return response()->json('delete classroom successfully');
    }
}
