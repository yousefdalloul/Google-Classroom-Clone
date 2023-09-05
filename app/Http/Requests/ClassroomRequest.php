<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('classroom',0);
        return [
            'name' => ['required','string','max:255',function($attribute,$value,$fail){
                if ($value == 'admin'){
                    return $fail('This name is forbidden!');
                }
            }],
            'code' => 'string',
            'section' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'room' => "nullable|string|max:255|unique:classrooms,room,$id",
            'cover_image' => [
                'nullable',
                'image',
                'max:1024',
                Rule::dimensions([
                    'min_width'=>600,
                    'min_height'=>250
                ]),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'required' => ':attribute required!',
            'name.required' => 'The name is required!',
            'cover_image.max'=> 'The size is grate than 1M'
        ];
    }
}
