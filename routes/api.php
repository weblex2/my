<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KnowledgeBaseController;
use App\Models\KnowledgeBase;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ContactController;

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

Route::get('apitest', function(){
    return 'Authentication';
});

Route::middleware('auth:api')->prefix('v1')->group( function(){

    Route::get('user', function(Request $request){
        return $request->user();
    });

    Route::controller(KnowledgeBaseController::class)->group(function (){

        /* Route::get('/kb/show', 'index' , function(KnowledgeBase $knowledgeBase){
            return $knowledgeBase;
        }); */
        Route::apiResource('knowledgeBase', KnowledgeBaseController::class);

        /* Route::get('/kb/show/{knowledgeBase}', 'show' , function(KnowledgeBase $knowledgeBase){
            return $knowledgeBase;
        }); */
    });



});




Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth.api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //Route::apiResource('resources', ResourceController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('contacts', ContactController::class);
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
