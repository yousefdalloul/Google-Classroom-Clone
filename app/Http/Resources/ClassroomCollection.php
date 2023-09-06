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
        $data = $this->collection->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
                'cover_image' => $model->cover_image_url,
                'meta' => [
                    'section' => $model->section,
                    'room' => $model->room,
                    'subject' => $model->subject,
                    'student_count' => $model->student_count ?? 0,
                    'theme' => $model->theme,
                ],
                'user' => [
                    'name' => $model->user?->name,
                ],
            ];
        });
    }
}
