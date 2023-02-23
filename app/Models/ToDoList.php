<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoList extends Model
{
    use HasFactory;
    protected $fillable =['name',];
   
    public function toDos(){
        return $this->hasMany(ToDo::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
