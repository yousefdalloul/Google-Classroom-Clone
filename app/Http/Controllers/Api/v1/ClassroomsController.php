<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Couchbase\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
        }

        $classroom = Classroom::with('user:id,name','topics')
            ->withCount('students as students')
            ->paginate(2);
        return ClassroomResource::collection($classroom);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.create')){
            abort(403);
        }

        $request->validate([
            'name'=>['required'],
        ]);

        $classroom = Classroom::create( $request->all() );
        return Response::json([
            'code'=> 100,
            'message' => __('Classrooms Created.'),
            'classroom' => $classroom,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    //    public function show(string $id)
    //    {
    //        return Classroom::findOrFail($id);
    //    }


    //  Model binding
    public function show(Classroom $classroom)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
        }

        $classroom->load('user')->loadCount('students');
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.update')){
            abort(403);
        }

        $request->validate([
            'name'=>['sometimes','required',Rule::unique('classrooms','name')->ignore($classroom->id)],
            'section'=>['sometimes','required']
        ]);

        $classroom->update($request->all());
        return [
            'code'=> 100,
            'message' => __('Classrooms updated.'),
            'classroom' => $classroom,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('classrooms.delete')){
            abort(403,'You cannot delete this classroom');
        }

        Classroom::destroy($id);
        return Response::json([],204);
        //        return [
        //          'code' => 100,
        //            'message'=> __('Classrooms Deleted'),
        //        ];
    }
}
