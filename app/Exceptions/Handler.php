<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $dontReport = [
        UserAlreadyJoinedClassroomExcption::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
//        $this->reportable(function (Throwable $e) {
//            Log::info($e->getMessage(),[
//                'user_id'=>Auth::user(),
//                'classroom_id' => $e->getClassroomId(),
//            ]);
////            return false;
//        })->stop();

        $this->reportable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                $errors = [];
                foreach ($e->errors() as $input => $err ){
                    $errors[$input] = collect($err)->first();
                }
                return response()->json($errors, 422);
            }
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->validator);
        });


//        $this->reportable(function (UserAlreadyJoinedClassroomExcption $e, Request $request){
//            if ($request->expectsJson()){
//                return response()->json([
//                    'message'=>$e->getMessage(),
//                ], 400);
//            }
//            return redirect()->route('classrooms.show',$e->getClassroomId());
//        });
    }
}
