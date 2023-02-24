<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreToDoRequest;
use App\Models\ToDo;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $order = $this->getTheHighestOrderInTheList($toDoList) + 1;

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
     * Gets the order of the highest non embeded ToDo in a ToDoList.
     * @param \App\Models\ToDoList $toDoList
     * @return Int returns -1 if list is empty
     */
    private function getTheHighestOrderInTheList(ToDoList $toDoList){
        $result = -1;
        foreach($toDoList->toDos as $toDo){
            
            if($toDo->isEmbedded() == false){
                if($result < $toDo->id){
                    $result = $toDo->id;
                }
            }
        }
        return $result;
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
