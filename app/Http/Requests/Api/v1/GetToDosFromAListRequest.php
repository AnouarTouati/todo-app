<?php

namespace App\Http\Requests\Api\v1;

use App\Models\ToDoList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetToDosFromAListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $toDoList = ToDoList::find($this->route('list_id'));
        if($toDoList){
            if($toDoList->user->id == Auth::user()->id){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
