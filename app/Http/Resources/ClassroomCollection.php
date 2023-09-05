<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function Symfony\Component\Translation\t;

class ClassroomCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'code' => $this->code,
            'cover_image'=> $this->cover_image_url,
            'meta' => [
                'section'       => $this->section,
                'room'          => $this->room,
                'subject'       => $this->subject,
                'student_count' => $this->student_count ?? 0,
                'theme'         => $this->theme,
            ],
            'user'=>[
                'name' =>$this->user->name,
            ],
        ];
    }
}
