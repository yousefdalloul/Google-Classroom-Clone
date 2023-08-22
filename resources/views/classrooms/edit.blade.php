@extends('layouts.master')

@section('title','Classrooms')

@section('content')
<div class= "container">
    <h1>Edit Classroom!</h1>

    <form action="{{route('classrooms.update',$classroom->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        <!--Form Method Sppofing -->
{{--        <input type="hidden" name="_method" value="put">--}}
{{--        {{ method_field('put') }}--}}
        @method('PUT')

        @include('classrooms._form',[
            'button_label' => 'Upload Classroom'
        ])
    </form>
</div>
@endsection