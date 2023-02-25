<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoList extends Model
{
    use HasFactory;
    protected $fillable =['name'];
   
    public function toDos(){
        return $this->hasMany(ToDo::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Decrements the order field of all ToDos that are higher in order from the removed ToDo.
     * @param \App\Models\ToDo $toDo
     * @return void
     */
    public function rearrangeAfterToDoIsRemoved(ToDo $toDo){
        $affectedToDos = $this->toDos()->where('order','>',$toDo->order)->get();
        foreach($affectedToDos as $affectedToDo){
            $affectedToDo->order -= 1;
            $affectedToDo->save();
        }
    }
}
