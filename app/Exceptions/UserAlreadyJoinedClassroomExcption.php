<?php

namespace App\Exceptions;

use App\Mail\CriticalError;
use App\Notifications\CriticalErrorNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class UserAlreadyJoinedClassroomExcption extends Exception
{
    protected $classroomId;
    public function setClassroomId($id)
    {
        $this->classroomId = $id;
        return $this;
    }
    public function getClassroomId()
    {
        return $this->classroomId;
    }

    public function report()
    {
        Notification::route('mail',config('app.errors_mail'))
            ->route('vonage',config('app.errors_phone'))
            ->notify(new CriticalErrorNotification($this));
        Mail::to(config('app.errors_mail'))->send(
            new CriticalError($this)
        );
        return false;
    }

    public function render(Request $request, )
    {
        if ($request->expectsJson()){
            return response()->json([
                'message'=>$this->getMessage(),
            ], 400);
        }
        return redirect()->route('classrooms.show',$this->getClassroomId());
    }
}
