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
        // Route::post('/to-do-lists/{list-id}/swap/{to-do-1-id}/{to-do-2-id}',[ToDoListController::class,'swap']);

        Route::put('/to-do/{id}',[ToDoController::class,'update']);
        Route::post('/to-do',[ToDoController::class,'store']);
        Route::delete('/to-do/{id}',[ToDoController::class,'destroy']);

        Route::post('/logout',[AuthController::class,'logout']);
    });
   
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
