<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MailController;

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
        return redirect()->route('blog.index');
    })->name('dashboard');
});

Route::get('/send-mail', [MailController::class, 'index']);

Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'index')->name('blog.index');
    Route::get('/blog/create', 'create')->middleware(['auth'])->name('blog.create');
    Route::post('/blog/store', 'store')->middleware(['auth'])->name('blog.store');
    Route::get('/blog/edit/{id}', 'edit')->middleware(['auth'])->name('blog.edit');
    Route::post('/blog/update', 'update')->middleware(['auth'])->name('blog.update');
    Route::post('/blog/delete', 'destroy')->middleware(['auth'])->name('blog.delete');
    Route::post('/blog/makeComment', 'makeComment')->name('blog.makeComment');
    Route::get('blog/newComment/{id}', 'commentForm')->name('blog.newComment');
    Route::post('/blog/deleteComment', 'deleteComment')->name('blog.deleteComment');
});   






Route::get('/', [BlogController::class, 'index']);
//Route::get('/dashboard', [BlogController::class, 'index']);




