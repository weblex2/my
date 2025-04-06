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
use App\Http\Controllers\yt2mp3;
use App\Http\Controllers\VideoController;
use App\Events\Hallo;
use App\Http\Controllers\S3Controller;
use App\Http\Controllers\WebsocketController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\FutterController;
use App\Http\Controllers\reactTutorialController;
use App\Http\Controllers\ShowSourcecodeController;
use App\Http\Controllers\MaintainanceController;
use App\Http\Controllers\GoogleGemeniController;
use App\Http\Controllers\FilamentFieldsController;
use App\Livewire\GoogleGemeni;
use App\Http\Controllers\LaravelMyAdminController;
use App\Http\Controllers\GitController;




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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/phpmyadmin', function () {
    return redirect('https://noppal.de:8081');
})->middleware(['auth'])->name('phpmyadmin');


Route::get('/phpinfo', function () {
    return phpinfo();
})->middleware(['auth'])->name('phpinfo');

Route::get('/', function () {
    return view('index');
});

Route::get('/arcade', function () {
    return view('arcade.index');
});

Route::get('/check-env', function () {
    return env('AWS_ACCESS_KEY_ID');
});



Route::controller(GoogleGemeniController::class)->group(function () {
    Route::get('/askGemeni', 'index')->name('gemeni.index');
    Route::post('/queryGemeni', 'query')->name('gemeni.query');
});

Route::controller(MaintainanceController::class)->group(function () {
    Route::get('/maintainance/backupDB', 'backupDatabase')->name('maintainance.backupdb');
    Route::get('/logs', 'showLogs')->middleware(['auth'])->name('maintainance.showLogs');
    Route::post('/logs', 'refreshLogs')->middleware(['auth'])->name('maintainance.refreshLogs');
     Route::get('/maintainance/diskinfo', 'diskinfo')->name('maintainance.diskInfo');
});


Route::controller(S3Controller::class)->group(function () {
    Route::get('/s3/test', 'index')->name('s3.index');
    Route::get('/s3/show/filename/{filename}', 'show')->name('s3.show');
    Route::get('/s3/uploadtest', 'uploadtest')->name('s3.uploadtest');
});

Route::controller(TestController::class)->group(function () {
    Route::get('/gpt', 'chatGptApi')->name('cv.index');
    Route::get('/fill/product', 'testProduct')->name('testProduct');
});

Route::controller(FutterController::class)->group(function () {
    Route::get('/futter', 'index')->name('futter.index');
    Route::get('/futter/new', 'new')->name('futter.new');
    Route::get('/futter/all', 'showAll')->name('futter.all');
    Route::get('/futter/{id}', 'showDetails')->name('futter.detail');
    Route::post('/futter/save', 'save')->name('futter.save');
    Route::post('/futter/update', 'update')->name('futter.update');
    Route::post('/futter/saveFutterPerDay', 'saveFutterPerDay')->name('futter.saveFutterPerDay');
});

Route::controller(CvController::class)->group(function () {
    Route::get('/cv', 'index')->name('cv.index');
    Route::get('/cv/json/{edit?}', 'json')->name('cv.json');
    Route::post('/cv/saveJson/', 'saveJson')->name('cv.savejson');
    Route::get('/cv/downloadJson/', 'downloadJson')->name('cv.downloadjson');
    Route::post('/cv/uploadJson/', 'uploadJson')->name('cv.uploadjson');
    Route::get('/cv/edit', 'edit')->name('cv.edit');
    Route::get('/cv/getPdf', 'pdf')->name('cv.pdf');
    Route::get('/manu/cv', 'indexm')->name('cv.indexm');
});

Route::controller(GalleryController::class)->group(function () {
    Route::get('/travel-blog', 'index')->name('gallery.index');
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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        //return view('dashboard');
        return redirect()->route('blog.index');
    })->name('dashboard');
});

Route::get('/send-mail', [MailController::class, 'index']);

Route::controller(TwilioController::class)->group(function () {
    Route::get('/wa', 'send')->name('sendWhatsapp');
});


Route::controller(FilamentFieldsController::class)->group(function () {
    Route::get('/filament/manageFields/{table?}', 'index')->middleware(['auth'])->name('filament.managefields');;
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'index')->name('blog.index');
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



Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

Route::get('/home', [FileUploadController::class, 'index']);
Route::post('/upload', [FileUploadController::class, 'uploadToServer']);
Route::post('/FileUpload', [FileUploadController::class, 'FileUpload'])->name('FileUpload');


//Queue Test
Route::controller(QueueController::class)->group(function () {
    Route::get('testJob', 'index')->name('job.test');
    Route::post('createJob', 'createJob')->name('job.createJob');
    //Route::get('email-test', 'index')->name('queue.test');
    Route::get('Jobs', 'showJobs')->name('showJobs');
});

Route::get('/react', function () {
    return view('react.index');
});

Route::get('/assd', function () {
    return view('assd');
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
Route::controller(FriesenController::class)->group(function () {
    Route::get('/friese', 'index')->name('friese.index');
    Route::get('getFriesen/{plz?}', 'getFriesen')->name('getfriesen');
    //Route::post('createFriese', 'createFriese')->name('firese.create');
});


Route::controller(reactTutorialController::class)->group(function () {
    Route::get('/react/Tutorial', 'index')->name('reacttutorial.index');
    Route::get('/react/Counter', 'counter')->name('reacttutorial.counter');
    Route::post('/react/getMessage', 'getMessage')->name('reacttutorial.getMessage');
    Route::get('/react/cv', 'cv')->name('reacttutorial.cv');
    Route::get('/react/cvtest', 'test')->name('cv.test');
});


Route::controller(yt2mp3::class)->group(function () {
    Route::GET('/yt2mp3', 'index')->name('yt2mp3.index');
    Route::POST('/yt2mp3/get', 'getmp3')->name('yt2mp3.getMp3');
    Route::GET('/yt2mp3/download/{file}', 'downloadFile')->name('yt2mp3.download');
    Route::GET('/yt2mp3/download2/', 'download2')->name('yt2mp3.download2');
});

Route::controller(NtfyController::class)->group(function () {
    Route::GET('/notify/getDates/{date?}', 'getDates')->name('ntfy.getDates');
    Route::GET('/notify/msg/{msg}', 'index')->name('ntfy.index');
    Route::GET('/notify/create', 'createNotification')->middleware(['auth'])->name('ntfy.create');
    Route::POST('/notify/store', 'storeNotification')->middleware(['auth'])->name('ntfy.store');
    Route::GET('/notify/test', 'test')->name('ntfy.test');
    Route::POST('/notify/sendTestMessage', 'sendTestMessage')->name('ntfy.sendTestMessage');
    Route::GET('/notify/show', 'showAll')->middleware(['auth'])->name('ntfy.showAll');
    Route::GET('/notify/show/{id}', 'show')->middleware(['auth'])->name('ntfy.show');
    Route::GET('/notify/new', 'new')->middleware(['auth'])->name('ntfy.new');
    Route::POST('/notify/save', 'save')->middleware(['auth'])->name('ntfy.save');
    Route::GET('/notify/edit/{id}', 'editNotification')->middleware(['auth'])->name('ntfy.editNotification');
    Route::POST('/notify/delete', 'deleteNotification')->middleware(['auth'])->name('ntfy.deleteNotification');
    Route::GET('/notify/sendNotifications', 'sendNotifications')->name('ntfy.sendNotifications');
});

Route::controller(KnowledgeBaseController::class)->group(function () {
    Route::GET('/kb', 'all')->middleware(['auth'])->name('knowledeBase.index');
    Route::GET('/kb/add', 'addWeb')->middleware(['auth'])->name('knowledeBase.add');
    Route::POST('/kb/store', 'storeWeb')->middleware(['auth'])->name('knowledeBase.store');
    Route::POST('/kb/delete', 'deleteWeb')->middleware(['auth'])->name('knowledeBase.delete');
    Route::GET('/kb/show/{id}', 'showWeb')->middleware(['auth'])->name('knowledgeBase.webshow');
});

Route::controller(VideoController::class)->group(function () {
    Route::GET('/video', 'index')->name('video.index');
    Route::GET('/video/create', 'create')->name('video.create');
});


Route::controller(WebsocketController::class)->group(function () {
    Route::GET('/message/{user_id?}', 'message')->name('message.view');
    Route::POST('/message/send-message', 'store')->name('message.send');
});

Route::controller(ChatController::class)->group(function () {
    Route::GET('/chat', 'showLogin')->name('chat.showLogin');
    Route::POST('/chat', 'login')->name('chat.login');
    Route::post('/chat/send-message', 'store')->name('chat.send');
    Route::get('/chat/test', 'test')->name('chat.test');
    Route::get('/chat/send/{msg}',  'sendMessage')->name('chat.sendMessage');
});




Route::controller(ShowSourcecodeController::class)->group(function () {
    Route::get('/ssc/{id}', 'index')->name('showSourceCode.index');
    Route::post('/ssc/getCode', 'getCode')->name('showSourceCode.getCode');
});


Route::controller(LaravelMyAdminController::class)->group(function () {
    Route::get('/laravelMyAdmin',  'index')->middleware(['auth'])->name('laravelMyAdmin.index');
    Route::get('/laravelMyAdmin/db/{db}','dbindex')->middleware(['auth'])->name('laravelMyAdmin.viewDatabase');
    Route::get('/laravelMyAdmin/edit/{table}','edit')->middleware(['auth'])->name('laravelMyAdmin.edit');
    Route::get('/laravelMyAdmin/showTableStructure/{db}/{table}','showTableStructure')->middleware(['auth'])->name('laravelMyAdmin.showTableStructure');
    Route::get('/laravelMyAdmin/new-table/{db}','newTable')->middleware(['auth'])->name('laravelMyAdmin.newTable');
    Route::post('/laravelMyAdmin/add-rows-to-table','addRowsToTable')->middleware(['auth'])->name('laravelMyAdmin.addRowsToTable');
    Route::post('/laravelMyAdmin/create-table','createTable')->middleware(['auth'])->name('laravelMyAdmin.createTable');
    Route::post('/laravelMyAdmin/modify-table','modifyTable')->middleware(['auth'])->name('laravelMyAdmin.modifyTable');
    Route::post('/laravelMyAdmin/generate-migration',  'generateMigration')->middleware(['auth'])->name('laravelMyAdmin.generateMigration');
    Route::post('/laravelMyAdmin/test-migration','execMigration')->middleware(['auth'])->name('laravelMyAdmin.testMigration');
    Route::get('/laravelMyAdmin/testmig','testMigration')->middleware(['auth'])->name('laravelMyAdmin.testmig');
    Route::get('/laravelMyAdmin/tools','tools')->middleware(['auth'])->name('laravelMyAdmin.tools');
    Route::get('/laravelMyAdmin/migrations','showMigrations')->middleware(['auth'])->name('laravelMyAdmin.migrations');
    Route::post('/laravelMyAdmin/exec-migration','execMigration')->middleware(['auth'])->name('laravelMyAdmin.execMigration');
    Route::get('/laravelMyAdmin/table-content/{db}/{table}','showTableContent')->middleware(['auth'])->name('laravelMyAdmin.showTableContent');
    Route::get('/laravelMyAdmin/clearCache','clearCache')->middleware(['auth'])->name('laravelMyAdmin.clearCache');

});

Route::get('/git/pull', [GitController::class, 'pull'])->name('git.pull');
Route::get('/git/npm/{type}',[GitController::class, 'npm'])->middleware(['auth'])->name('laravelMyAdmin.npm');
