<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Test;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View as BaseView;
use Mockery\Generator\Method;

class ClassroomController extends Controller
{
    //Action
    public function index(Request $request) : Renderable
    {
        //return Collection of classroom
        $classrooms = Classroom::orderBy('created_at','DESC')->get();
        return view('classrooms.index',compact('classrooms'));

    }


    public function create()
    {
        return view()->make('classrooms.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        //Method 1
        $classroom = new Classroom();
        $classroom->name = $request->post('name');
        $classroom->section = $request->post('section');
        $classroom->subject = $request->post('subject');
        $classroom->room = $request->post('room');
        $classroom->code = Str::random(8);
        $classroom->save(); //insert


        //Method2: Mass assignment
//        $data = $request->all();
//        $data = Str::random(8);
//        $classroom = Classroom::create( $data );

        $request->merge([
            'code'=>Str::random(8),
        ]);
        $classroom = Classroom::create($request->all());


        //After all Post process make PRG :Post Redirect Get
        return redirect()->route('classrooms.index');
    }

    public function show(string $id)
    {
        // $classroom = Classroom::where('id','=',$id)->first();
        $classroom = Classroom::findOrFail($id);

        return View::make('classrooms.show')
                ->with([
                    'classroom'=> $classroom ,
                ]);
    }

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('classrooms.edit', [
            'classroom' => $classroom,
        ]);
    }

    public function update(Request $request,$id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->name = $request->post('name');
        $classroom->section = $request->post('section');
        $classroom->subject = $request->post('subject');
        $classroom->room = $request->post('room');
        $classroom->save(); //insert

        //Mass assignment
//        $classroom->update($request->all());

//        $classroom->fill($request->all())->save();

        return Redirect::route('classrooms.index');
    }

    public function destroy($id)
    {
        Classroom::destroy($id);

//        Classroom::where('id','=',$id)->delete();

//        $classroom = Classroom::find($id);
//        $classroom->delete();

        return redirect(route('classrooms.index'));

    }
}
