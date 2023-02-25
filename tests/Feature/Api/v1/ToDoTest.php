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

        $response = $this->post('/api/v1/to-dos',['to_do_list_id'=>$toDoList->id,
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
      

        $response = $this->delete('/api/v1/to-dos/'.$toDo->id,[],
                            ['Accept'=>'application/json',
                            'Authorization'=>'Bearer '.$token
                        ]);
        
        $response->assertStatus(204);

    }

    public function test_delete_a_to_do_from_a_filled_list()
    {
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;

 
        $toDoList = new ToDoList();
        $toDoList->user()->associate($user);
        $toDoList->save();
        $toDoList= $user->toDoLists()->orderBy('id', 'desc')->first();

        for($i=0;$i<10;$i++){
            $toDo = new ToDo();
            $toDo->toDoList()->associate($toDoList);
            $toDo->title = 'a title';
            $toDo->description = 'descr';
            
            $toDo->order = $i;
            $toDo->save();
        }
      
        $toDo = $toDoList->toDos()->where('order', 6)->first();
      

        $response = $this->delete('/api/v1/to-dos/'.$toDo->id,[],
                            ['Accept'=>'application/json',
                            'Authorization'=>'Bearer '.$token
                        ]);
        
        $response->assertStatus(204);
        $this->assertEquals(9,$toDoList->toDos()->count());
        $toDos = $toDoList->toDos()->orderBy('order','asc')->get();
        for($i=0;$i<9;$i++){
            $this->assertEquals($i, $toDos[$i]->order);
        }
    }

    public function test_update_a_to_do(){
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;

 
        $toDoList = new ToDoList();
        $toDoList->user()->associate($user);
        $toDoList->save();
        $toDoList= $user->toDoLists()->orderBy('id', 'desc')->first();

        $toDo= new Todo();
        $toDo->title='title1';
        $toDo->order = 0;
        $toDo->toDoList()->associate($toDoList);
        $toDo->save();
        $toDo= $toDoList->toDos()->orderBy('id', 'desc')->first();

        $response = $this->put('/api/v1/to-dos/'.$toDo->id,['title'=>'title2',
                                                ],
                            ['Accept'=>'application/json',
                            'Authorization'=>'Bearer '.$token
                        ]);
        
        $response->assertStatus(204);
        $toDo->refresh();
        $this->assertEquals('title2',$toDo->title);
        
    }
    public function test_view_a_to_do(){
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;

 
        $toDoList = new ToDoList();
        $toDoList->user()->associate($user);
        $toDoList->save();
        $toDoList= $user->toDoLists()->orderBy('id', 'desc')->first();

        $toDo= new Todo();
        $toDo->title='title1';
        $toDo->order = 0;
        $toDo->toDoList()->associate($toDoList);
        $toDo->save();
        $toDo= $toDoList->toDos()->orderBy('id', 'desc')->first();

        $response = $this->get('/api/v1/to-dos/'.$toDo->id,
                            ['Accept'=>'application/json',
                            'Authorization'=>'Bearer '.$token
                        ]);
        
        $response->assertStatus(200);
        $id= $response->json('id');
        $this->assertEquals($toDo->id,$id);
        
    }
}
