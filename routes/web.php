<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomPeopleController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TopicsController;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','password.confirm'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function (){
    Route::prefix('/classrooms/trashed')
        ->as('classrooms.')
        ->controller(ClassroomController::class)
        ->group(function (){
            Route::get('/','trashed')->name('trashed');
            Route::put('/{classroom}','restore')->name('restore');
            Route::delete('/{classroom}','forceDelete')->name('force-delete');
        });

    Route::get('join/{classroom}', [JoinClassroomController::class, 'create'])
        ->middleware('signed')
        ->name('classrooms.join');
    Route::post('join/{classroom}', [JoinClassroomController::class, 'store']);

    Route::resources([
        'topics'=>TopicsController::class,
        'classrooms'=>ClassroomController::class,
    ]);

    // Nested resource for classworks within classrooms
    Route::resource('classrooms.classworks', ClassworkController::class);

    Route::get('classrooms/{classroom}/people',[ClassroomPeopleController::class,'index'])
            ->name('classrooms.people');
    Route::delete('classrooms/{classroom}/people',[ClassroomPeopleController::class,'destroy'])
            ->name('classrooms.people.destroy');

    Route::post('comments',[CommentController::class,'store'])
        ->name('comments.store');

    Route::post('classwork/{classwork}/submissions',[SubmissionController::class,'store'])
        ->name('submissions.store');

    Route::get('/submissions/{submission}',[SubmissionController::class,'file'])
        ->name('submissions.file');
});

require __DIR__.'/auth.php';

