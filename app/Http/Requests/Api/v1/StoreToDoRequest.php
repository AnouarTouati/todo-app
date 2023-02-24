<?php

namespace App\Http\Requests\Api\v1;

use App\Rules\ToDoListExists;
use Illuminate\Foundation\Http\FormRequest;

class StoreToDoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'to_do_list_id'=>['required','numeric',new ToDoListExists],
            'title'=>'required|string|max:255|min:1',
            'description'=>'nullable|string',
            'end_date'=>'nullable|date_format:Y-m-d'
        ];
    }
}
