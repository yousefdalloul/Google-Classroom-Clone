<?php

namespace App\Listeners;

use App\Events\ClassworkCreated;
use App\Models\Stream;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PostInClassroomStream
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClassworkCreated $event): void
    {
        $classwork = $event->classwork;

        $content = __(':name posted a new :type: :title', [
            'name'   => $classwork->user->name,
            'type'  => __($classwork->type->value),
            'title' => $classwork->title,
        ]);

        try {
            $stream = new Stream([
                'classroom_id' => $classwork->classroom_id,
                'user_id'      => $event->classwork->user_id,
                'content'      => $content,
                'link'         => route('classrooms.classworks.show', [
                    $classwork->classroom_id,
                    $classwork->id,
                ]),
            ]);
            $stream->save();
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it or throw a custom exception)
        }
    }
}
