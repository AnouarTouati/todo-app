<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreToDoListRequest;
use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $toDoLists = Auth::user()->toDoLists;
      return response(json_encode($toDoLists),200)->withHeaders(['Content-Type'=>'application/json']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToDoListRequest $request)
    {
        if($request->name == null){
            $request->replace(['name'=>'a to do list']);
        }
       $toDoList = new ToDoList();
       $toDoList->user()->associate(Auth::user());
       $toDoList->save();

       return response(json_encode(['toDoList'=>$toDoList]),201)->withHeaders(['Content-Type'=>'application/json']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $toDoList = Auth::user()->toDoLists()->find($id);
       return response(json_encode($toDoList),200)->withHeaders(['Content-Type'=>'application/json']);
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
        ToDoList::destroy($id);
        return response([],204);
    }
}
