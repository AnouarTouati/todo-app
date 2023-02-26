<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ToDoController;
use App\Http\Controllers\Api\v1\ToDoListController;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix'=>'v1'],function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login'])->name('login');
    
    Route::group(['middleware'=>'auth:sanctum'],function(){
        Route::get('/to-do-lists',[ToDoListController::class,'index']);
        Route::get('/to-do-lists/{id}',[ToDoListController::class,'show']);
        Route::post('/to-do-lists',[ToDoListController::class,'store']);
        Route::delete('/to-do-lists/{id}',[ToDoListController::class,'destroy']);
        Route::post('/to-do-lists/move/{movingtodo}/above/{targettodo}',[ToDoListController::class,'moveAbove']);
        Route::post('/to-do-lists/move/{movingtodo}/below/{targettodo}',[ToDoListController::class,'moveBelow']);
       //Update todo list name route
      
        Route::get('/to-dos/list/{list_id}',[ToDoController::class,'index']);
        Route::get('/to-dos/{id}',[ToDoController::class,'show']);
        Route::put('/to-dos/{id}',[ToDoController::class,'update']);
        Route::post('/to-dos',[ToDoController::class,'store']);
        Route::delete('/to-dos/{id}',[ToDoController::class,'destroy']);

        Route::post('/logout',[AuthController::class,'logout']);
    });
   
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
