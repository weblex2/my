<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\FriesenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () { 
    Route::get('/dashboard', function () {
        //return view('dashboard');
        return redirect()->route('blog.index');
    })->name('dashboard');
});

Route::get('/send-mail', [MailController::class, 'index']);

Route::controller(TwilioController::class)->group(function () {
    Route::get('/wa', 'send')->name('sendWhatsapp');
});    

Route::controller(BlogController::class)->group(function () {
    Route::get('/', 'index')->name('blog.index');
    //Route::get('/blog', 'index')->name('blog.index');
    Route::get('/blog/cat/{id}', 'showcat')->name('blog.showcat');
    Route::get('/blog/create', 'create')->middleware(['auth'])->name('blog.create');
    Route::post('/blog/store', 'store')->middleware(['auth'])->name('blog.store');
    Route::get('/blog/edit/{id}', 'edit')->middleware(['auth'])->name('blog.edit');
    Route::post('/blog/update', 'update')->middleware(['auth'])->name('blog.update');
    Route::post('/blog/delete', 'destroy')->middleware(['auth'])->name('blog.delete');
    Route::post('/blog/makeComment', 'makeComment')->name('blog.makeComment');
    Route::get('blog/newComment/{id}', 'commentForm')->name('blog.newComment');
    Route::post('/blog/deleteComment', 'deleteComment')->name('blog.deleteComment');
    Route::GET('/blog/reactTest', 'reactTest')->name('blog.reactText');
});   

Route::controller(CalendarController::class)->group(function () {
    Route::get('/calendar', 'index')->name('calendar.index'); 
    Route::get('/calendar/save', 'store')->name('calendar.saveEvent'); 
});    
   


Route::controller(FacebookController::class)->group(function(){
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

Route::get('/home', [FileUploadController::class, 'index']);
Route::post('/upload', [FileUploadController::class, 'uploadToServer']);


//Queue Test
Route::controller(QueueController::class)->group(function(){
    Route::get('testJob', 'index')->name('job.test');
    Route::post('createJob', 'createJob')->name('job.createJob');
    //Route::get('email-test', 'index')->name('queue.test');
    Route::get('Jobs', 'showJobs')->name('showJobs');
});

Route::get('/react', function () {
    return view('react.index');
}); 

Route::get('/react/ajax', function () {
    return view('react.index');
}); 

Route::domain('friese24.noppal.de')->group(function () {
    Route::controller(FriesenController::class)->group(function(){
        Route::get('/friese', 'index')->name('friese.index');
    });
});

//Friesen 
Route::controller(FriesenController::class)->group(function(){
    Route::get('/friese', 'index')->name('friese.index');
    Route::get('getFriesen/{plz?}', 'getFriesen')->name('getfriesen');  
    //Route::post('createFriese', 'createFriese')->name('firese.create');
});