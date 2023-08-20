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


        <div class= "form-floating mb-3">
            <input type="text" class="form-control" value="{{ $classroom->name }}" name = "name" id="name" placeholder="Class Name">
            <label for="name">Class Name</label>
        </div>
        <img src="storage/{{ $classroom->cover_image_path }}" alt="">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" value="{{ $classroom->section }}" name="section" id="section" placeholder="Section">
            <label for="section" >Section</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" value="{{ $classroom->subject }}" name = "subject" id="subject" placeholder="Subject">
            <label for="subject">Subject</label>

        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" value="{{ $classroom->room }}" name="room" id="room" placeholder="Room">
            <label for="section" >Room</label>
        </div>
        <img src="{{ Storage::disk('public')->url($classroom->cover_image_path) }}" alt="">

        <div class="form-floating mb-3">
            <input type="file" class="form-control" name="cover_image" id="cover_image" placeholder="Cover Image">
            <label for="section" >Cover Image</label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection