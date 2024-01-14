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
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NtfyController;
use App\Http\Controllers\KnowledgeBaseController;

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

Route::get('/arcade', function () {
    return view('arcade.index');
}); 

Route::controller(GalleryController::class)->group(function () {
    Route::get('/travel-blog','index')->name('gallery.index');
    Route::get('/travel-blog/index2', 'index2')->name('gallery.index2');
    Route::get('/travel-blog/create', 'create')->name('gallery.create');
    Route::get('/travel-blog/edit', 'edit')->name('gallery.edit');
    Route::post('/travel-blog/store', 'store')->name('gallery.store');
    Route::post('/travel-blog/delete', 'delete')->name('gallery.delete');
    Route::get('/travel-blog/show/{id}/{mappoint_id?}', 'showGallery')->name('gallery.showGallery');
    Route::get('/showMore/{offset?}', 'showMore')->name('gallery.showMore');
    Route::get('/travel-blog/upload/{gallery_id}/{mappoint_id?}', 'upload')->name('gallery.upload');
    Route::get('/travel-blog/editpic/{pic_id}', 'editpic')->name('gallery.editPic');
    Route::post('/travel-blog/updatepic', 'updatepic')->name('gallery.updatePic');
    Route::post('/travel-blog/storepic', 'storePic')->name('gallery.storepic');
    Route::post('/travel-blog/deletepic', 'deletePic')->name('gallery.deleteBlogItem');
    Route::post('/travel-blog/delete', 'delete')->name('gallery.delete');
    Route::get('/travel-blog/createMapPoint', 'createGalleryMappoint')->name('gallery.createMappoint');
    Route::get('/travel-blog/editMapPoint', 'editGalleryMappoints')->name('gallery.editMappoint');
    Route::post('/travel-blog/storeMapPoint', 'storeGalleryMappoint')->name('gallery.storeMappoint');
    Route::post('/travel-blog/deleteMapPoint', 'deleteGalleryMappoint')->name('gallery.deleteMappoint');
    Route::post('/travel-blog/setLang', 'setLang')->name('gallery.setLang');
    Route::get('/travel-blog/editMappointPics/{mp_id}', 'editMappointPics')->name('gallery.editMappointPics');
    Route::post('/travel-blog/updatePicOrder', 'updatePicOrder')->name('gallery.updatePicOrder');
    Route::get('/travel-blog/picTest', 'picTest')->name('gallery.picTest');
    Route::get('/travel-blog/config', 'config')->name('gallery.config');
    Route::post('/travel-blog/storeConfig', 'storeConfig')->name('gallery.storeConfig');
    Route::get('/travel-blog/getBigPic/{id}', 'getBigPic')->name('gallery.getBigPic');
});    

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

/* Route::domain('friese24.noppal.de')->group(function () {
    Route::controller(FriesenController::class)->group(function(){
        Route::get('/friese', 'index')->name('friese.index');
    });
}); */

//Friesen 
Route::controller(FriesenController::class)->group(function(){
    Route::get('/friese', 'index')->name('friese.index');
    Route::get('getFriesen/{plz?}', 'getFriesen')->name('getfriesen');  
    //Route::post('createFriese', 'createFriese')->name('firese.create');
});


Route::controller(NtfyController::class)->group(function () {
    Route::GET('/notify/getDates/{date?}', 'getDates')->name('ntfy.getDates');  
    Route::GET('/notify/msg/{msg}', 'index')->name('ntfy.index');    
    Route::GET('/notify/create', 'createNotification')->name('ntfy.create'); 
    Route::GET('/notify/store', 'storeNotification')->name('ntfy.store');
});

Route::controller(KnowledgeBaseController::class)->group(function () {
    Route::GET('/kb/test', 'kbtest')->name('kb.test');
});
