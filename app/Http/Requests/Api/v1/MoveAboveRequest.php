<?php

namespace App\Http\Requests\Api\v1;

use App\Models\ToDo;
use Illuminate\Foundation\Http\FormRequest;

class MoveAboveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $movingToDo = ToDo::find($this->route('movingtodo'));
        $targetToDo = ToDo::find($this->route('targettodo'));
        
        if($movingToDo && $targetToDo){
            if ($movingToDo->toDoList->id == $targetToDo->toDoList->id) {
                if($movingToDo->toDoList->user->id == $this->user()->id){
                    return true;
                }
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
