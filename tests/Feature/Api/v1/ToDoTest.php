<?php

namespace Tests\Feature\Api\v1;

use App\Models\ToDo;
use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ToDoTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_a_to_do()
    {
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;

 
        $toDoList = new ToDoList();
        $toDoList->user()->associate($user);
        $toDoList->save();

        $toDoList= $user->toDoLists()->orderBy('id', 'desc')->first();

        $response = $this->post('/api/v1/to-do',['to_do_list_id'=>$toDoList->id,
                                                    'title'=>'A to do',
                                                    'description'=>'Lorem .....',
                                                    'end_date'=>'2020-07-01'
                                                ],
                            ['Accept'=>'application/json',
                            'Authorization'=>'Bearer '.$token
                        ]);
        
        $response->assertStatus(201);

        $toDoListId = $response->json('to_do_list_id');
        $this->assertEquals($toDoList->id,$toDoListId);
        $toDoTitle = $response->json('title');
        $this->assertEquals('A to do',$toDoTitle);
    }

    public function test_delete_a_to_do()
    {
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;

 
        $toDoList = new ToDoList();
        $toDoList->user()->associate($user);
        $toDoList->save();
        $toDoList= $user->toDoLists()->orderBy('id', 'desc')->first();

        $toDo = new ToDo();
        $toDo->toDoList()->associate($toDoList);
        $toDo->title = 'a title';
        $toDo->description = 'descr';
        
        $toDo->order = 0;
        $toDo->save();
        $toDo = $toDoList->toDos()->orderBy('id', 'desc')->first();
      

        $response = $this->delete('/api/v1/to-do/'.$toDo->id,[],
                            ['Accept'=>'application/json',
                            'Authorization'=>'Bearer '.$token
                        ]);
        
        $response->assertStatus(204);

    }
}
