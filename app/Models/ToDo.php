<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'end_date'
    ];
    public function toDoList(){
        return $this->belongsTo(ToDoList::class);
    }

    public function parentToDo(){
        return $this->belongsTo(ToDo::class);
    }

    /**
     * Checks where this ToDo is embedded under another todo
     * @return Boolean isEmbedded
     */
    public function isEmbedded(){
        return $this->parentToDo != null;
    }
}
