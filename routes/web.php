<?php

use App\Http\Controllers\ClassroomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/','welcome');
Route::get('/', function () {
    return view('welcome')
        ->name('home');
});
Route::get('/classrooms',[ClassroomController::class,'index'])
            ->name('classrooms.index');

Route::get('/classrooms/edit',[ClassroomController::class,'edit'])
    ->name('classrooms.edit');

Route::get('/classrooms/create',[ClassroomController::class,'create'])
    ->name('classrooms.create');

Route::get('/classrooms/{classroom}/{dark?}',[ClassroomController::class,'show'])
    ->name('classrooms.show')
    -> where('classroom','\d+') //Regular expression
    -> where('dark','yes|no');

//            every time we need YourClass we should pass the dependency manually
//            $instance = new YourClass($dependency);


//            Service Container.
//            //add a binding for the class YourClass
//            App::bind( YourClass::class, function()
//            {
//                //do some preliminary work: create the needed dependencies
//                $dependency = new DepClass( config('some.value') );
//
//                //create and return the object with his dependencies
//                return new YourClass( $dependency );
//            });
