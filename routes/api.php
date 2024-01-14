<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KnowledgeBaseController;
use App\Models\KnowledgeBase;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
