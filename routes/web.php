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
    return view('welcome');
      })->name('home');
//
//Route::get('/classrooms',[ClassroomController::class,'index'])
//            ->name('classrooms.index');
//
//Route::get('/classrooms/create',[ClassroomController::class,'create'])
//    ->name('classrooms.create');
//
//Route::post('/classrooms',[ClassroomController::class,'store'])
//    ->name('classrooms.store');
//
//Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])
//    ->name('classrooms.show')
//    ->where('classroom', '\d+');  // Regular expression for numeric values
//    //->where('dark', 'yes|no'); // Only allow 'yes' or 'no' for the 'dark' parameter
//
//Route::get('/classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])
//    ->name('classrooms.edit')
//    ->where('classroom', '\d+');
//
//Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])
//    ->name('classrooms.update')
//    ->where('classroom', '\d+');
//
//Route::put('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])
//    ->name('classrooms.destroy')
//    ->where('classroom', '\d+');

Route::resource('/classrooms',ClassroomController::class)->names([

    ]);