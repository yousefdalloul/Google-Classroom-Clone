<?php

use App\Http\Controllers\Admin\TwoFactorAuthenticationController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassroomPeopleController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\Webhooks\StripeController;
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

Route::get('/admin/2fa', [TwoFactorAuthenticationController::class, 'create']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','password.confirm'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('plans',[PlansController::class,'index'])
    ->name('plan');

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

    Route::get('classrooms/{classroom}/chat',[ClassroomController::class,'chat'])->name('classrooms.chat');


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
        //->middleware('can:create,App\Model\Classwork');

    Route::get('/submissions/{submission}',[SubmissionController::class,'file'])
        ->name('submissions.file');

    Route::post('subscriptions',[SubscriptionsController::class,'store'])
        ->name('subscriptions.store');

    Route::post('payments',[PaymentsController::class,'store'])
        ->name('payments.store');

    Route::get('/payments/{subscription}/success',[PaymentsController::class,'success'])
        ->name('payments.success');

    Route::get('/payments/{subscription}/cancel',[PaymentsController::class,'cancel'])
        ->name('payments.cancel');

    Route::get('subscriptions/{subscription}/pay', [PaymentsController::class, 'create'])
        ->name('checkout');
});

//require __DIR__.'/auth.php';

Route::post('/payments/stripe/webhook',StripeController::class);

