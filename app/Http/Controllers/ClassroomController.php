<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Test;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View as BaseView;
use Mockery\Generator\Method;

class ClassroomController extends Controller
{
    //Action
    public function index(Request $request) : Renderable
    {
        //return Collection of classroom
        $classrooms = Classroom::orderBy('created_at','DESC')->get();

        $success = session('success'); //return value of success in the session

        return view('classrooms.index',compact('classrooms','success'));

    }


    public function create()
    {
        return view()->make('classrooms.create',[
            'classroom'=>new Classroom(),
        ]);
    }

    public function store(ClassroomRequest $request) : RedirectResponse
    {

        //Method 1
        $classroom = new Classroom();
//        $classroom->name = $request->post('name');
//        $classroom->section = $request->post('section');
//        $classroom->subject = $request->post('subject');
//        $classroom->room = $request->post('room');
//        $classroom->code = Str::random(8);
//        $classroom->save(); //insert


        //Method2: Mass assignment
//        $data = $request->all();
//        $data = Str::random(8);
//        $classroom = Classroom::create( $data );

        if ($request->hasFile('cover_image')){
            $file = $request->file('cover_image');  //UploadedFile

            $path = Classroom::uploadCoverImage($file);
//            $request->merge([
//                'cover_image_path'=>$path,
//            ]);
            $validated['cover_image_path'] = $path;


//            $request->merge([
//                'code'=>Str::random(8),
//            ]);
            $validated['code'] = Str::random(8);
        }


        $classroom = Classroom::create($validated);


        //After all Post process make PRG :Post Redirect Get
        return redirect()->route('classrooms.index')
            ->with('success','Classroom Created!');
    }

    public function show(Classroom $classroom)
    {
        // $classroom = Classroom::where('id','=',$id)->first();
        //$classroom = Classroom::findOrFail($id);

        return View::make('classrooms.show')
                ->with([
                    'classroom'=> $classroom ,
                ]);
    }

    public function edit(Classroom $classroom)
    {
        //$classroom = Classroom::findOrFail($id);
        return view('classrooms.edit', [
            'classroom' => $classroom,
        ]);
    }

    public function update(ClassroomRequest $request,Classroom $classroom)
    {

        $validated = $request->validated();


        //$classroom = Classroom::findOrFail($id);
//        $classroom->name = $request->post('name');
//        $classroom->section = $request->post('section');
//        $classroom->subject = $request->post('subject');
//        $classroom->room = $request->post('room');
//        $classroom->save(); //insert

        if ($request->hasFile('cover_image')){
            $file = $request->file('cover_image');  //UploadedFile
            //Solution 1:
//            $name = $classroom->cover_image_path ?? (Str::random(40) . '.' . $file->getClientOriginalExtension());
//            $path = $file->storeAs('/covers',basename($name),[
//                'disk'=>Classroom::$disk
//            ]);

            //Solution 2:
            $path = Classroom::uploadCoverImage($file);

//            $request->merge([
//                'cover_image_path'=>$path,
//            ]);

            $validated['cover_image_path'] = $path;
        }

        //Mass assignment
        $classroom->update($validated);

        //Solution 2: cont ..
        $old = $classroom->cover_image_path;
        if ($old && $old != $classroom->cover_image_path){
            Classroom::deleteCoverImage($old);
        }

        //$classroom->fill($request->all())->save();

        Session::flash('success','Classroom updated!');
        Session::flash('error','Test for error message!');
        return Redirect::route('classrooms.index');
            //->with('success','Classroom Updated!')
            //->with('error','Classroom Updated!');
    }

    public function destroy(Classroom $classroom)
    {
//        Classroom::destroy($id);
//        Classroom::where('id','=',$id)->delete();

//        $classroom = Classroom::find($id);
        $classroom->delete();
        Classroom::deleteCoverImage($classroom->cover_image_path);

        return redirect(route('classrooms.index'))
            ->with('success','Classroom Deleted!');
    }
}
