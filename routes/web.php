<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/* without logon */
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'index')->name('blog.index');
});

/* only logged in */
Route::middleware(['auth:sanctum', 'verified'])->controller(BlogController::class)->group(function () {
    Route::get('/blog/{id}', 'edit')->name('blog.edit');
    Route::get('/blog/create', 'create')->name('blog.create');
    Route::post('/blog/save', 'store')->name('blog.save');
});