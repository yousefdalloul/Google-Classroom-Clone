<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    //Action
    public function index(){
        //return response :view ,redirect,json-data,file,String;
        return view('classrooms.index',[
            'name'=>'Yousef',
            'title'=>' Web Dev'
        ]);
    }

    public function create()
    {
        return view('classrooms.create');
    }
    public function show($id,$edit = false)
    {
        return view('classrooms.show',[
            'id'=>$id,
            'edit'=>$edit
            ]);
    }
}
