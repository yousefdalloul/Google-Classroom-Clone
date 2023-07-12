<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View as BaseView;

class ClassroomController extends Controller
{
    //Action
    public function index(Request $request) : Renderable
    {
        $name ='Yousef';
        $title = 'Dev';
        //return response :view ,redirect,json-data,file,String;
        //return Redirect::away('http://google.com');
        return view('classrooms.index', compact('name','title'));
    }
    public function create()
    {
        return view()->make('classrooms.create');
    }
    public function show(Request $request,int $id,String $dark = 'no')
    {
        return View::make('classrooms.show')
                ->with([
                    'id'=> $id,
                    'dark' => $dark
                ]);
    }
    public function edit()
    {
        return view('classrooms.edit');
    }
}
