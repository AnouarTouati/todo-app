<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\DeleteToDoRequest;
use App\Http\Requests\Api\v1\GetToDosFromAListRequest;
use App\Http\Requests\Api\v1\StoreToDoRequest;
use App\Http\Requests\Api\v1\UpdateToDoRequest;
use App\Models\ToDo;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetToDosFromAListRequest $request)
    {
        $toDoList = ToDoList::find($request->list_id);
        return response($toDoList->toDos,'200')->withHeaders([
            'Content-Type'=>'application/json'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToDoRequest $request)
    {
        $toDoList = Auth::user()->toDoLists()->find($request->to_do_list_id);
        if($toDoList->toDos()->count() > 0){
            $order = $toDoList->toDos()->max('order') + 1;
        }else{
            //if no rows exists then order is 0
            $order = 0;
        }
       

        $toDo = new ToDo();
        $toDo->toDoList()->associate($toDoList);
        $toDo->title = $request->title;
        $toDo->description = $request->description;
        $toDo->end_date = Carbon::create($request->end_date);
        $toDo->order = $order;
        $toDo->save();

        return response(json_encode($toDo),201);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $toDo = ToDo::find($id);
        if(Auth::user()->can('view',$toDo)){
            return response(json_encode($toDo,200));
        }
        return response('',404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateToDoRequest $request, $id)
    {
        $toDo = ToDo::find($id);
        if(Auth::user()->can('update',$toDo)){
            if($request->title){
                $toDo->title = $request->title;
            }
            if($request->description){
                $toDo->title = $request->description;
            }
            if(isset($request->toggled)){
                $toDo->title = $request->toggled;
            }
            if($request->end_date){
                $toDo->title = Carbon::create($request->end_date);
            }
            $toDo->save();
            
            return response('',204);
        }
        return response('',404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $toDo = ToDo::find($id);
       
        if(Auth::user()->can('delete',$toDo)){
            $toDo->toDoList->rearrangeAfterToDoIsRemoved($toDo);
            ToDo::destroy($id);
            return response('',204);
        }
        
        return response('',404);
    }

}
