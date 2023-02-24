<?php

namespace Tests\Feature\Api\v1;

use App\Models\ToDoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ToDoListTest extends TestCase
{
  
    use RefreshDatabase;
    
    public function test_create_a_to_do_list()
    {
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;

        $response = $this->post('/api/v1/to-do-lists',[],['Accept'=>'application/json','Authorization'=>'Bearer '.$token]);
        
        $response->assertStatus(201);
        $response->assertJsonStructure(['toDoList'=>['id']]);
    }

    public function test_delete_a_to_do_list(){
        $toDoList = new ToDoList();
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;
        $toDoList->user()->associate($user);
        $toDoList->save();
        $toDoList= ToDoList::orderBy('id', 'desc')->first();

        $reponse = $this->delete('/api/v1/to-do-lists/'.$toDoList->id,[],['Accept'=>'application/json','Authorization'=>'Bearer '.$token]);

        $reponse->assertStatus(204);
    }

    public function test_get_to_do_lists(){
      
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;
        $numberOfLists = 10;
        for($i=0;$i< $numberOfLists;$i++){
            $toDoList = new ToDoList();
            $toDoList->user()->associate($user);
            $toDoList->save();
        }

        $reponse = $this->get('/api/v1/to-do-lists',['Accept'=>'application/json','Authorization'=>'Bearer '.$token]);

        $reponse->assertStatus(200);
        
        $toDoLists = $reponse->json();
       
        $this->assertEquals($numberOfLists,count($toDoLists));
    }
   
    public function test_get_a_to_do_list(){
      
        $user = User::create(['email'=>'client@test.com']);
        $token = $user->createToken('token123')->plainTextToken;
        $numberOfLists = 10;
        for($i=0;$i< $numberOfLists;$i++){
            $toDoList = new ToDoList();
            $toDoList->user()->associate($user);
            $toDoList->save();
        }
        $toDoList= $user->toDoLists()->orderBy('id', 'desc')->first();

        $reponse = $this->get('/api/v1/to-do-lists/'.$toDoList->id,['Accept'=>'application/json','Authorization'=>'Bearer '.$token]);

        $reponse->assertStatus(200);
        
        $id = $reponse->json('id');
       
        $this->assertEquals($toDoList->id,$id);
    }
}
