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
use App\Http\Controllers\GalleryAdminController;
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
use App\Http\Controllers\ZimbraController;
use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\SchafkopfController;
use App\Http\Controllers\WegController;
use App\Http\Controllers\GameController;



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

// Rollen mit Schreib-/Verwaltungsrechten (Spatie). Nur 'auth' reicht nicht:
// bis 2026-11-15 war die Registrierung offen, und fremde Konten ohne Rolle
// waren damit "eingeloggt". Grundsatz seitdem: oeffentlich ist nur, was zur
// Website gehoert - alles Interne, Tests und Werkzeuge laufen ueber $adminOnly.
$adminOnly = ['auth', 'role:super_admin|admin'];

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/phpmyadmin', function () {
    return redirect('https://noppal.de:8081');
})->middleware($adminOnly)->name('phpmyadmin');


Route::get('/phpinfo', function () {
    return phpinfo();
})->middleware($adminOnly)->name('phpinfo');

Route::get('/', function () {
    return view('index');
});

Route::get('/2', function () {
    return view('index2');
});

Route::get('/index2', function () {
    return view('index2');
});

Route::get('/index3', function () {
    return view('index3');
});

Route::get('/arcade', function () {
    return view('arcade.index');
});

// /check-env gab den AWS Access Key oeffentlich aus - entfernt (2026-11-15)

Route::controller(GoogleGemeniController::class)->group(function () {
    Route::get('/askGemeni', 'index')->name('gemeni.index');
    // kostet API-Guthaben: begrenzen
    Route::post('/queryGemeni', 'query')->middleware('throttle:10,1')->name('gemeni.query');
});

Route::controller(MaintainanceController::class)->middleware($adminOnly)->group(function () {
    Route::get('/maintainance/backupDB', 'backupDatabase')->name('maintainance.backupdb');
    Route::get('/logs', 'showLogs')->name('maintainance.showLogs');
    Route::post('/logs', 'refreshLogs')->name('maintainance.refreshLogs');
    Route::get('/maintainance/diskinfo', 'diskinfo')->name('maintainance.diskInfo');
    Route::get('/maintainance/redisInsight', 'redisInsight')->name('maintainance.redisInsight');
    Route::get('/maintainance/cert', 'showCertLog')->name('maintainance.showCertLog');
    Route::get('/maintainance/certlog2db', 'certLog2DB')->name('maintainance.certlog2db');
});


Route::controller(S3Controller::class)->middleware($adminOnly)->group(function () {
    Route::get('/s3/test', 'index')->name('s3.index');
    Route::get('/s3/show/filename/{filename}', 'show')->name('s3.show');
    Route::get('/s3/uploadtest', 'uploadtest')->name('s3.uploadtest');
});

Route::controller(TestController::class)->middleware($adminOnly)->group(function () {
    Route::get('/gpt', 'chatGptApi')->name('cv.index');
    Route::get('/test', 'testModel')->name('testProduct');
    Route::get('/check', 'check')->name('check');
    Route::get('/redischeck', 'testRedis')->name('testRedis');
    Route::get('/testEmail', 'testEmail')->name('testEmail');
    Route::get('/sendEmail', 'sendEmail')->name('sendEmail');
});

Route::controller(FutterController::class)->group(function () use ($adminOnly) {
    // Lesen oeffentlich, Anlegen/Aendern nur Admins (ueber /futter/save kamen
    // bis 2026-11-15 Webshells auf den Server)
    Route::get('/futter', 'index')->name('futter.index');
    Route::get('/futter/all', 'showAll')->name('futter.all');
    Route::get('/futter/new', 'new')->middleware($adminOnly)->name('futter.new');
    Route::get('/futter/{id}', 'showDetails')->name('futter.detail');
    Route::post('/futter/save', 'save')->middleware($adminOnly)->name('futter.save');
    Route::post('/futter/update', 'update')->middleware($adminOnly)->name('futter.update');
    Route::post('/futter/saveFutterPerDay', 'saveFutterPerDay')->middleware($adminOnly)->name('futter.saveFutterPerDay');
});

Route::controller(CvController::class)->group(function () {
    Route::get('/cv', 'index')->name('cv.index2');
    Route::get('/cv/json/{edit?}', 'json')->name('cv.json');
    Route::post('/cv/saveJson/', 'saveJson')->name('cv.savejson');
    Route::get('/cv/downloadJson/', 'downloadJson')->name('cv.downloadjson');
    Route::post('/cv/uploadJson/', 'uploadJson')->name('cv.uploadjson');
    Route::get('/cv/edit', 'edit')->name('cv.edit');
    Route::get('/cv/getPdf', 'pdf')->name('cv.pdf');
    Route::get('/manu/cv', 'indexm')->name('cv.indexm');
});

// TEMP DEBUG — GPS dump (remove after debugging)
Route::middleware($adminOnly)->post('/dev/exif-dump', function(\Illuminate\Http\Request $request) {
    if (!$request->hasFile('file')) return response()->json(['error' => 'no file']);
    $file = $request->file('file');
    $path = $file->getRealPath();
    $ext  = strtolower($file->getClientOriginalExtension());

    $id3 = [];
    try {
        $track = new \Owenoj\LaravelGetId3\GetId3($path);
        $id3   = $track->extractInfo();
    } catch(\Exception $e) { $id3 = ['error' => $e->getMessage()]; }

    $native = [];
    if (in_array($ext, ['jpg','jpeg','tiff','tif'])) {
        $native = @exif_read_data($path, 0, true) ?: [];
    }

    return response()->json([
        'ext'    => $ext,
        'id3'    => $id3,
        'native' => $native,
    ]);
});

Route::controller(GalleryController::class)->group(function () use ($adminOnly) {
    // Oeffentlich: Anzeigen und Sprache
    Route::get('/travel-blog', 'index')->name('gallery.index');
    Route::get('/travel-blog/index2', 'index2')->name('gallery.index2');
    Route::get('/travel-blog/index3', 'index3')->name('gallery.index3');
    Route::get('/travel-blog/map-data/{gallery_id}', 'getMapData')->name('gallery.mapdata');
    Route::get('/travel-blog/mappoint-photos/{mappoint_id}', 'getMappointPhotos')->name('gallery.mappointphotos');
    Route::get('/travel-blog/show/{id}/{mappoint_id?}', 'showGallery')->name('gallery.showGallery');
    Route::get('/showMore/{offset?}', 'showMore')->name('gallery.showMore');
    Route::post('/travel-blog/setLang', 'setLang')->name('gallery.setLang');
    Route::get('/travel-blog/getBigPic/{id}', 'getBigPic')->name('gallery.getBigPic');
    Route::get('/travel-blog/image/{pic_id}/{size}', 'serveImage')->name('gallery.serveImage');

    // Schreiben/Verwalten: waren bis 2026-11-15 ganz ohne Schutz
    Route::middleware($adminOnly)->group(function () {
        Route::get('/travel-blog/upload-bulk/{gallery_id?}/{mappoint_id?}', 'uploadBulk')->name('gallery.uploadBulk');
        Route::post('/travel-blog/store-bulk', 'storeBulk')->name('gallery.storeBulk');
        Route::post('/travel-blog/assign-pic', 'assignPic')->name('gallery.assignPic');
        Route::get('/travel-blog/create', 'create')->name('gallery.create');
        Route::get('/travel-blog/edit', 'edit')->name('gallery.edit');
        Route::post('/travel-blog/store', 'store')->name('gallery.store');
        Route::post('/travel-blog/delete', 'delete')->name('gallery.delete');
        Route::get('/travel-blog/upload/{gallery_id}/{mappoint_id?}', 'upload')->name('gallery.upload');
        Route::get('/travel-blog/editpic/{pic_id}', 'editpic')->name('gallery.editPic');
        Route::post('/travel-blog/updatepic', 'updatepic')->name('gallery.updatePic');
        Route::post('/travel-blog/storepic', 'storePic')->name('gallery.storepic');
        Route::post('/travel-blog/deletepic', 'deletePic')->name('gallery.deleteBlogItem');
        Route::get('/travel-blog/createMapPoint', 'createGalleryMappoint')->name('gallery.createMappoint');
        Route::get('/travel-blog/editMapPoint', 'editGalleryMappoints')->name('gallery.editMappoint');
        Route::post('/travel-blog/storeMapPoint', 'storeGalleryMappoint')->name('gallery.storeMappoint');
        Route::post('/travel-blog/deleteMapPoint', 'deleteGalleryMappoint')->name('gallery.deleteMappoint');
        Route::get('/travel-blog/editMappointPics/{mp_id}', 'editMappointPics')->name('gallery.editMappointPics');
        Route::post('/travel-blog/updatePicOrder', 'updatePicOrder')->name('gallery.updatePicOrder');
        Route::get('/travel-blog/picTest', 'picTest')->name('gallery.picTest');
        Route::get('/travel-blog/config', 'config')->name('gallery.config');
        Route::post('/travel-blog/storeConfig', 'storeConfig')->name('gallery.storeConfig');
    });
});

// Gallery Admin Routes
// Login zwingend: die Gruppe kann Galerien, Mappoints und Fotos loeschen und
// war bis 2026-08-09 ohne Middleware, also oeffentlich erreichbar. Gleiches
// `auth` wie bei den tb2-Routen.
Route::prefix('gallery-admin')->middleware($adminOnly)->group(function () {
    Route::controller(GalleryAdminController::class)->group(function () {
        // Dashboard
        Route::get('/', 'index')->name('gallery.admin.index');

        // Gallery CRUD
        Route::post('/gallery', 'storeGallery')->name('gallery.admin.storeGallery');
        Route::put('/gallery/{id}', 'updateGallery')->name('gallery.admin.updateGallery');
        Route::delete('/gallery/{id}', 'deleteGallery')->name('gallery.admin.deleteGallery');

        // Mappoint CRUD
        Route::get('/mappoints/{galleryId}', 'getMappoints')->name('gallery.admin.getMappoints');
        Route::post('/mappoint', 'storeMappoint')->name('gallery.admin.storeMappoint');
        Route::put('/mappoint/{id}', 'updateMappoint')->name('gallery.admin.updateMappoint');
        Route::delete('/mappoint/{id}', 'deleteMappoint')->name('gallery.admin.deleteMappoint');
        Route::post('/mappoint/reorder', 'reorderMappoints')->name('gallery.admin.reorderMappoints');

        // Photo Management
        Route::get('/photos/{mappointId?}', 'getPhotos')->name('gallery.admin.getPhotos');
        Route::delete('/photo/{id}', 'deletePhoto')->name('gallery.admin.deletePhoto');
        Route::post('/photos/bulk-delete', 'bulkDeletePhotos')->name('gallery.admin.bulkDeletePhotos');
        Route::post('/photos/assign', 'assignPhotos')->name('gallery.admin.assignPhotos');
        Route::put('/photo/text/{id}', 'updatePhotoText')->name('gallery.admin.updatePhotoText');
        Route::put('/photo/date/{id}', 'updatePhotoDate')->name('gallery.admin.updatePhotoDate');
        Route::post('/photos/reorder', 'reorderPhotos')->name('gallery.admin.reorderPhotos');

        // Search
        Route::get('/search', 'search')->name('gallery.admin.search');
    });

    // Test Upload Page
    Route::get('/test', function() {
        return view('gallery.test_upload');
    });
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        //return view('dashboard');
        return redirect()->route('blog.index');
    })->name('dashboard');
});

Route::get('/send-mail', [MailController::class, 'index'])->middleware($adminOnly);

Route::controller(TwilioController::class)->middleware($adminOnly)->group(function () {
    Route::get('/wa', 'send')->name('sendWhatsapp');
});


Route::controller(FilamentFieldsController::class)->middleware($adminOnly)->group(function () {
    Route::get('/filament/manageFields/{table?}', 'index')->name('filament.managefields');
});

Route::controller(BlogController::class)->group(function () use ($adminOnly) {
    Route::get('/blog', 'index')->name('blog.index');
    Route::get('/blog/cat/{id}', 'showcat')->name('blog.showcat');
    Route::get('/blog/create', 'create')->middleware($adminOnly)->name('blog.create');
    Route::post('/blog/store', 'store')->middleware($adminOnly)->name('blog.store');
    Route::get('/blog/edit/{id}', 'edit')->middleware($adminOnly)->name('blog.edit');
    Route::post('/blog/update', 'update')->middleware($adminOnly)->name('blog.update');
    Route::post('/blog/delete', 'destroy')->middleware($adminOnly)->name('blog.delete');
    Route::post('/blog/makeComment', 'makeComment')->middleware('throttle:10,1')->name('blog.makeComment');
    Route::get('blog/newComment/{id}', 'commentForm')->name('blog.newComment');
    Route::post('/blog/deleteComment', 'deleteComment')->middleware($adminOnly)->name('blog.deleteComment');
    Route::GET('/blog/reactTest', 'reactTest')->middleware($adminOnly)->name('blog.reactText');
});

Route::controller(CalendarController::class)->middleware($adminOnly)->group(function () {
    Route::get('/calendar', 'index')->name('calendar.index');
    Route::get('/calendar/save', 'store')->name('calendar.saveEvent');
});



Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

// Uploads: nahmen bis 2026-11-15 ohne Login jeden Dateityp nach public/ an
Route::get('/home', [FileUploadController::class, 'index'])->middleware($adminOnly);
Route::post('/upload', [FileUploadController::class, 'uploadToServer'])->middleware($adminOnly);
Route::post('/FileUpload', [FileUploadController::class, 'FileUpload'])->middleware($adminOnly)->name('FileUpload');


//Queue Test
Route::controller(QueueController::class)->middleware($adminOnly)->group(function () {
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


// yt2mp3: die URL geht ungeprueft an yt-dlp ("--exec ..." waere ein Befehl) - nur Admins
Route::controller(yt2mp3::class)->middleware($adminOnly)->group(function () {
    Route::GET('/yt2mp3', 'index')->name('yt2mp3.index');
    Route::POST('/yt2mp3/get', 'getmp3')->name('yt2mp3.getMp3');
    Route::GET('/yt2mp3/download/{file}', 'downloadFile')->name('yt2mp3.download');
    Route::GET('/yt2mp3/download2/', 'download2')->name('yt2mp3.download2');
});

// NTFY: sendNotifications laeuft evtl. per Cron ueber HTTP - siehe Hinweis im Bericht
Route::controller(NtfyController::class)->middleware($adminOnly)->group(function () {
    Route::GET('/notify/getDates/{date?}', 'getDates')->name('ntfy.getDates');
    Route::GET('/notify/msg/{msg}', 'index')->name('ntfy.index');
    Route::GET('/notify/create', 'createNotification')->name('ntfy.create');
    Route::POST('/notify/store', 'storeNotification')->name('ntfy.store');
    Route::GET('/notify/test', 'test')->name('ntfy.test');
    Route::POST('/notify/sendTestMessage', 'sendTestMessage')->name('ntfy.sendTestMessage');
    Route::GET('/notify/show', 'showAll')->name('ntfy.showAll');
    Route::GET('/notify/show/{id}', 'show')->name('ntfy.show');
    Route::GET('/notify/new', 'new')->name('ntfy.new');
    Route::POST('/notify/save', 'save')->name('ntfy.save');
    Route::GET('/notify/edit/{id}', 'editNotification')->name('ntfy.editNotification');
    Route::POST('/notify/delete', 'deleteNotification')->name('ntfy.deleteNotification');
    Route::GET('/notify/sendNotifications', 'sendNotifications')->name('ntfy.sendNotifications');
});

Route::controller(KnowledgeBaseController::class)->middleware($adminOnly)->group(function () {
    Route::GET('/kb', 'all')->name('knowledeBase.index');
    Route::GET('/kb/add', 'addWeb')->name('knowledeBase.add');
    Route::POST('/kb/store', 'storeWeb')->name('knowledeBase.store');
    Route::POST('/kb/delete', 'deleteWeb')->name('knowledeBase.delete');
    Route::GET('/kb/show/{id}', 'showWeb')->name('knowledgeBase.webshow');
});

Route::controller(VideoController::class)->group(function () use ($adminOnly) {
    Route::GET('/video', 'index')->name('video.index');
    Route::GET('/video/create', 'create')->middleware($adminOnly)->name('video.create');
});


Route::controller(WebsocketController::class)->group(function () {
    Route::GET('/message/{user_id?}', 'message')->name('message.view');
    Route::POST('/message/send-message', 'store')->name('message.send');
});

Route::controller(ChatController::class)->group(function () use ($adminOnly) {
    Route::GET('/chat', 'showLogin')->name('chat.showLogin');
    Route::POST('/chat', 'login')->name('chat.login');
    Route::post('/chat/send-message', 'store')->name('chat.send');
    Route::get('/chat/test', 'test')->middleware($adminOnly)->name('chat.test');
    Route::get('/chat/send/{msg}',  'sendMessage')->name('chat.sendMessage');
});




Route::controller(ShowSourcecodeController::class)->group(function () {
    Route::get('/ssc/{id}', 'index')->name('showSourceCode.index');
    Route::post('/ssc/getCode', 'getCode')->name('showSourceCode.getCode');
});


// Eigener DB-Admin inkl. SQL-Ausfuehrung: nur Admin-Rollen
Route::controller(LaravelMyAdminController::class)->middleware($adminOnly)->group(function () {
    Route::get('/laravelMyAdmin',  'index')->middleware(['auth'])->name('laravelMyAdmin.index');
    Route::get('/laravelMyAdmin/db/{db}','dbindex')->middleware(['auth'])->name('laravelMyAdmin.viewDatabase');
    Route::get('/laravelMyAdmin/edit/{table}','edit')->middleware(['auth'])->name('laravelMyAdmin.edit');
    Route::get('/laravelMyAdmin/showTableStructure/{db}/{table}','showTableStructure')->middleware(['auth'])->name('laravelMyAdmin.showTableStructure');
    Route::get('/laravelMyAdmin/editTableStructure','editTableStructure')->middleware(['auth'])->name('laravelMyAdmin.editTableStructure');
    Route::get('/laravelMyAdmin/new-table/{db}','newTable')->middleware(['auth'])->name('laravelMyAdmin.newTable');
    Route::post('/laravelMyAdmin/addRowsToTable','addRowsToTable')->middleware(['auth'])->name('laravelMyAdmin.addRowsToTable');
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
    Route::get('/laravelMyAdmin/sql','sql')->middleware(['auth'])->name('laravelMyAdmin.sql');
    Route::post('/laravelMyAdmin/sql','execsql')->middleware(['auth'])->name('laravelMyAdmin.execSql');

});

Route::get('/git/pull', [GitController::class, 'pull'])->middleware($adminOnly)->name('git.pull');
Route::get('/git/npm/{type}',[GitController::class, 'npm'])->middleware($adminOnly)->name('laravelMyAdmin.npm');


Route::controller(ZimbraController::class)->middleware($adminOnly)->group(function () {
    Route::post('/zimbra/emails', 'fetchTodayEmails')->name('zimbra.fetchTodayEmails');
    Route::get('/zimbra/emails','index')->name('zimbra.index');
    Route::get('/zimbra/saveEmails','saveEmails')->name('zimbra.saveEmails');
    Route::get('/zimbra/test','importEmails')->name('zimbra.importEmails');
});

// WEG-Dokumente: waren ohne Login per fortlaufender ID abrufbar
Route::get('/documents/{document}/download', [DocumentDownloadController::class, 'download'])->middleware($adminOnly)->name('documents.download');
Route::get('/pdf/view/{id}', [DocumentDownloadController::class, 'view'])->middleware($adminOnly)->name('pdf.view');

Route::controller(BankTransactionController::class)->middleware($adminOnly)->group(function () {
    Route::get('/bank-transactions', 'index')->name('bank-transactions.index');
    Route::get('/bank-transactions/upload', 'uploadForm')->name('bank-transactions.showupload');
    Route::post('/bank-transactions/upload', 'upload')->name('bank-transactions.upload');
});


Route::controller(SchafkopfController::class)->middleware($adminOnly)->group(function () {
    Route::get('/sk', 'index')->name('sk.index');
});


Route::controller(WegController::class)->middleware($adminOnly)->group(function () {
    Route::get('/weg', 'index')->name('weg.index');
    Route::get('/weg/upload', 'upload')->name('weg.upload');
    Route::post('/weg/store', 'upload')->name('weg.store');
    Route::get('/weg/preview/{id}', 'preview')->name('attachment.preview');
    Route::get('weg/content/{id}', 'getDocumentBody')->name('document.content');
    Route::get('/upload-modal', function () {return view('livewire.file-upload');})->name('upload.modal');
});

/*
|--------------------------------------------------------------------------
| Travel-Blog 2 - Weltkarte mit eingefaerbten Laendern + durchlaufender Feed
|--------------------------------------------------------------------------
| Bewusst getrennt vom bestehenden GalleryController-Block, damit
| /travel-blog unveraendert bleibt.
*/
Route::controller(\App\Http\Controllers\TravelBlog2Controller::class)->group(function () use ($adminOnly) {
    Route::get('/travel-blog2', 'index')->name('tb2.index');
    Route::get('/travel-blog2/countries', 'countries')->name('tb2.countries');
    Route::get('/travel-blog2/feed', 'feed')->name('tb2.feed');
    Route::get('/travel-blog2/upload', 'upload')->middleware($adminOnly)->name('tb2.upload');
    Route::post('/travel-blog2/store', 'store')->middleware($adminOnly)->name('tb2.store');
    Route::get('/travel-blog2/admin', 'admin')->middleware($adminOnly)->name('tb2.admin');
    Route::post('/travel-blog2/approve', 'approve')->middleware($adminOnly)->name('tb2.approve');
    Route::post('/travel-blog2/delete', 'destroy')->middleware($adminOnly)->name('tb2.delete');
});

Route::get('/futter2', fn() => view('futter2'));


