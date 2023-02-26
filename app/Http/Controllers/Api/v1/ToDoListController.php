<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\MoveAboveRequest;
use App\Http\Requests\Api\v1\StoreToDoListRequest;
use App\Models\ToDo;
use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        return response(json_encode($toDoLists), 200)->withHeaders(['Content-Type' => 'application/json']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToDoListRequest $request)
    {
        $toDoList = new ToDoList();
        $toDoList->user()->associate(Auth::user());
        $toDoList->name = $request->name;
        $toDoList->save();

        return response(json_encode(['toDoList' => $toDoList]), 201)->withHeaders(['Content-Type' => 'application/json']);
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
        return response(json_encode($toDoList), 200)->withHeaders(['Content-Type' => 'application/json']);
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
        return response([], 204);
    }

    public function moveAbove(MoveAboveRequest $request, $movingToDoId, $targetToDoId)
    {

        $movingToDo = ToDo::find($movingToDoId);
        $targetToDo = ToDo::find($targetToDoId);

        if ($targetToDo->order > $movingToDo->order) {
            $toDosThatNeedToChange = $movingToDo->toDoList->toDos()->where('order', '>', $movingToDo->order)->where('order', '<', $targetToDo->order)->get();

            Log::debug('Moving to do order ' . $movingToDo->order);
            Log::debug('Target to do order ' . $targetToDo->order);
            $movingToDo->order = $targetToDo->order - 1;
            $movingToDo->save();
            Log::debug('New Moving to do order ' . $movingToDo->order);

            foreach ($toDosThatNeedToChange as $toDo) {
                Log::debug('Order' . $toDo->order);
                Log::debug('Order became' . $toDo->order - 1);
                $toDo->order -= 1;
                $toDo->save();
            }

        } else {
            $toDosThatNeedToChange = $movingToDo->toDoList->toDos()->where('order', '>=', $targetToDo->order)->where('order', '<', $movingToDo->order)->get();

            Log::debug('Moving to do order ' . $movingToDo->order);
            Log::debug('Target to do order ' . $targetToDo->order);
            $movingToDo->order = $targetToDo->order;
            $movingToDo->save();
            Log::debug('New Moving to do order ' . $movingToDo->order);
            foreach ($toDosThatNeedToChange as $toDo) {
                Log::debug('Order' . $toDo->order);
                Log::debug('Order became' . $toDo->order + 1);
                $toDo->order += 1;
                $toDo->save();
            }
        }

        Log::debug('result');
        foreach ($movingToDo->toDoList->toDos()->orderBy('order', 'asc')->get() as $toDo) {
            Log::debug($toDo->order);
        }
        return response('', 204);
    }
    public function moveBelow(MoveAboveRequest $request, $movingToDoId, $targetToDoId)
    {
        Log::debug('move below called');
        $movingToDo = ToDo::find($movingToDoId);
        $targetToDo = ToDo::find($targetToDoId);

        if ($targetToDo->order > $movingToDo->order) {
            
            $toDosThatNeedToChange = $movingToDo->toDoList->toDos()->where('order', '>', $movingToDo->order)->where('order', '<=', $targetToDo->order)->get();

            Log::debug('Moving to do order ' . $movingToDo->order);
            Log::debug('Target to do order ' . $targetToDo->order);
            $movingToDo->order = $targetToDo->order;
            $movingToDo->save();
            Log::debug('New Moving to do order ' . $movingToDo->order);

            foreach ($toDosThatNeedToChange as $toDo) {
                Log::debug('Order' . $toDo->order);
                Log::debug('Order became' . $toDo->order - 1);
                $toDo->order -= 1;
                $toDo->save();
            }

        } else {
            $toDosThatNeedToChange = $movingToDo->toDoList->toDos()->where('order', '>', $targetToDo->order)->where('order', '<', $movingToDo->order)->get();

            Log::debug('Moving to do order ' . $movingToDo->order);
            Log::debug('Target to do order ' . $targetToDo->order);
            $movingToDo->order = $targetToDo->order +1;
            $movingToDo->save();
            Log::debug('New Moving to do order ' . $movingToDo->order);
            foreach ($toDosThatNeedToChange as $toDo) {
                Log::debug('Order' . $toDo->order);
                Log::debug('Order became' . $toDo->order + 1);
                $toDo->order += 1;
                $toDo->save();
            }
        }

        Log::debug('result');
        foreach ($movingToDo->toDoList->toDos()->orderBy('order', 'asc')->get() as $toDo) {
            Log::debug($toDo->order);
        }
        return response('', 204);
    }

}
