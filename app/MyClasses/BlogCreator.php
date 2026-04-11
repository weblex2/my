<?php
namespace App\MyClasses;

use DB;
use File;
use App\MyClasses\Misc as Misc;
use App\Models\GalleryPics;
use App\Models\GalleryText;
use App\Models\GalleryPicContent;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Owenoj\LaravelGetId3\GetId3;
use App\Http\Controllers\GalleryController;


class BlogCreator{

    var $manager;
    private $org;
    private $img;
    private $status;
    private $fileName;
    private $extention;
    private $imgPath;
    private $thumbs = [];
    private $tmpPath;
    private $mimeType;
    private $gallery_id;
    private $mappoint_id;
    private $pic_id;
    private $fullPathName;
    private $content;
    private $ratio;
    private $size;
    private $fullpath;
    private $originalName;
    private $tmpFileName;
    private $fileId;
    private $isVideo = false;
    private $request;
    private $tmpFolder;
    private $videos;
    private $autoAssign  = false;
    public  $assignResult = [];  // populated after savePic() in auto-assign mode

    function __construct($request) {

        $this->videos =['MOV','MP4'];

        $this->request = $request;
        // get the temp folder
        $this->tmpPath = Misc::getConfig('tmp_path');#
        if (isset($this->tmpPath[0]->value)){
            $this->tmpPath = $this->tmpPath[0]->value;
        }
        else{
            $this->tmpPath = storage_path("tmp");
        }

        // Filename
        $this->fileName = $request->file->getClientOriginalName();

        // Extention
        $this->extention = pathinfo( $this->fileName, PATHINFO_EXTENSION);

        // set Video flag
        if (in_array(strtoupper($this->extention), $this->videos)){
            $this->isVideo = true;
        }

        // Set Full Path & Name
        $this->fullPathName  = $this->tmpPath."/".$this->fileName;

        // Auto-assign mode: GPS/Timestamp → TripAutoAssigner bestimmt Gallery + Mappoint
        $this->autoAssign = (bool) ($request->auto_assign ?? false);

        if (!$this->autoAssign) {
            $this->gallery_id  = GalleryController::getGalIdFromCode($request->country_code);
            $this->mappoint_id = $request->mappoint_id;
        }

        $this->content = $request->content;
        $this->status  = 'INIT';
    }

    public function uploadFile(){
        $successfullyMoved = $this->request->file->move($this->tmpPath, $this->fileName);
        return $successfullyMoved;
    }

    public function loadMedia(){

        if ($this->isVideo ){   // Videos
            $this->mimeType = mime_content_type($this->fullPathName);
        }
        else{                   // Images
            $this->manager = new ImageManager(new Driver());
            $this->img = $this->manager->read($this->fullPathName);
            $this->mimeType = $this->img->origin()->mediaType();
            $this->size = $this->img->size();
            $this->ratio = $this->size->aspectRatio();
        }
    }

    public function createThumbNails(){

        // create a temporary folder for the thumbnails
        $this->tmpFolder = $this->tmpPath."/".pathinfo( $this->fileName, PATHINFO_FILENAME);
        if (!file_exists($this->tmpFolder)) {
            File::makeDirectory($this->tmpFolder, 0777, true, true);
        }

        // Get file sizes from config
        $thumbsizes = Misc::getConfig('pic_size%', 'value', 'DESC');
        $thumbsizes = $thumbsizes->sortByDesc('value');

        // FALLBACK: Wenn Config leer ist, verwende Default-Größen
        if ($thumbsizes->count() == 0) {
            \Log::warning('BlogCreator: No thumbnail config found, using defaults', ['file' => $this->fileName]);
            $thumbsizes = collect([
                (object)['option' => 'pic_size_xl', 'value' => 2000, 'value2' => null],
                (object)['option' => 'pic_size_l', 'value' => 1000, 'value2' => null],
                (object)['option' => 'pic_size_m', 'value' => 768, 'value2' => null],
                (object)['option' => 'pic_size_tn', 'value' => 100, 'value2' => 100],
            ]);
        }

        if (!$this->isVideo){   // Image

            foreach ($thumbsizes as $size){
                $type = explode('_', $size->option)[2];
                $name = $type.".".$this->extention;
                $filePath = $this->tmpFolder."/".$name;

                try {
                    // Create squared image
                    if ($size->value2!="") {
                        $res = $this->img->resize($size->value, $size->value2)->save($filePath);
                    }
                    // Create scaled image
                    else{
                        $res = $this->img->scale(width: $size->value)->save($filePath);
                    }

                    if (!file_exists($filePath)) {
                        \Log::error('BlogCreator: Thumbnail not created', [
                            'file' => $this->fileName,
                            'size' => $type,
                            'path' => $filePath
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('BlogCreator: Thumbnail creation failed', [
                        'file' => $this->fileName,
                        'size' => $type,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        else {   // Video — tmpFolder already created above, thumbnail done in createBlog()
        }

        return true;
    }

    private function savePic(){
        // Robuste EXIF-Extraktion (iPhone + Android, alle Formate)
        $exif    = (new ExifExtractor($this->fullPathName))->extract();
        $lat     = $exif['lat'];
        $lon     = $exif['lon'];
        $takenAt = $exif['taken_at'];

        // Keine GEO-Daten → Upload abbrechen
        if (!$lat || !$lon) {
            throw new \Exception('Keine GPS-Daten im Bild/Video gefunden. Upload abgelehnt.');
        }

        // GetId3 meta für das meta-JSON-Feld (bleibt für Kompatibilität)
        try {
            $track = new GetId3($this->fullPathName);
            $raw   = $track->extractInfo();
            $meta  = $this->sanitizeMetaForJson($raw);
        } catch (\Exception $e) {
            $meta = [];
        }

        // Auto-assign: GPS + Timestamp → Gallery & Mappoint automatisch bestimmen
        if ($this->autoAssign) {
            if (!$takenAt) {
                throw new \Exception('Kein Zeitstempel im Bild/Video gefunden. Upload abgelehnt.');
            }
            $assigner = new TripAutoAssigner();
            $this->assignResult = $assigner->assign($lat, $lon, $takenAt);
            $this->gallery_id   = $this->assignResult['gallery_id'];
            $this->mappoint_id  = $this->assignResult['mappoint_id'];
        }

        $this->pic_id = Misc::getPicId();
        $pic = new GalleryPics;
        $pic->gallery_id  = $this->gallery_id;
        $pic->mappoint_id = $this->mappoint_id;
        $pic->pic         = $this->fileName;
        $pic->meta        = !empty($meta) ? $meta : null;
        $pic->lon         = $lon;
        $pic->lat         = $lat;
        $pic->taken_at    = $takenAt;
        $pic->ord         = Misc::getPicOrder($this->mappoint_id);
        $res = $pic->save();
        $this->pic_id = $pic->id;
        return $res;
        //$lon = $this->getGps($img->exif('GPS')["GPSLongitude"], $img->exif('GPS')['GPSLongitudeRef']);
        //$lat = $this->getGps($img->exif('GPS')["GPSLatitude"], $img->exif('GPS')['GPSLatitudeRef']);
        //var_dump($lat, $lon);
    }

    private function savePicText(){
        $galText  = new GalleryText();
        $galText->pic_id = $this->pic_id;
        $galText->gallery_id = $this->gallery_id;
        $galText->mappoint_id = $this->mappoint_id;
        $content = str_replace('<a ', '<a target="_blank" ',$this->content);
        $galText->text =  $content;
        $galText->language = session('lang');
        $galText->save();
    }

    private function savePicContent($files){
        foreach ($files as $file){
             $fileName = pathinfo($file,  PATHINFO_FILENAME);
             $extention = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array($extention, $this->videos)){
                $size = 'MOV';
            }
            else{
                $size = $fileName;
            }
            $mimeType = mime_content_type($file);
            $pic  = new GalleryPicContent();
            $pic->pic_id = $this->pic_id;
            $pic->size = strtoupper($size);
            $pic->filecontent = "data:".$mimeType.";base64,".base64_encode(file_get_contents($file));
            $res = $pic->save();
        }
    }

    private function saveVideoContent(){
        $pic  = new GalleryPicContent();
        $pic->pic_id = $this->pic_id;
        $pic->size = 'MOV';
        $pic->filecontent = "data:".$this->mimeType.";base64,".base64_encode(file_get_contents($this->fullPathName));
        $res = $pic->save();
    }

    public function createBlog(){
        DB::beginTransaction();
        try {
            $this->savePic();
            $this->savePicText();

            if ($this->isVideo) {
                $this->createVideoThumbnails();          // frame → tmpFolder/videotn.jpg
                $this->saveVideoContent();               // MOV binary → DB (size=MOV)
                $tmpFiles = glob($this->tmpFolder."/*"); // videotn.jpg
            } else {
                $tmpFiles = glob($this->tmpFolder."/*"); // small/medium/big thumbnails
            }

            $this->savePicContent($tmpFiles);
        }
        catch(\Exception $e){
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
        DB::commit();
        $this->cleanup();
        return true;
    }

    public function createVideoThumbnails(): void
    {
        $thumbPath = $this->tmpFolder . '/videotn.jpg';
        $cmd = sprintf(
            'ffmpeg -y -i %s -ss 00:00:01.000 -vframes 1 -q:v 2 %s 2>&1',
            escapeshellarg($this->fullPathName),
            escapeshellarg($thumbPath)
        );
        exec($cmd);
        // If ffmpeg failed or isn't installed, continue without thumbnail
    }


    public function getPicId(): ?int
    {
        return $this->pic_id ?? null;
    }

    public function cleanup(){
        // Cleanup temporary files
        $tmpFiles = glob($this->tmpFolder.'/*');
        foreach ($tmpFiles as $file){
            unlink($file);
        }
        // remove folder
        File::deleteDirectory($this->tmpFolder);
        // remove original file
        unlink($this->fullPathName);
    }

    private function sanitizeMetaForJson($data): mixed
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->sanitizeMetaForJson($value);
            }
            return $result;
        }
        if (is_string($data)) {
            // mb_scrub ersetzt ungültige UTF-8-Sequenzen (z.B. binäre Thumbnails)
            return mb_scrub($data, 'UTF-8');
        }
        return $data;
    }

    public function gps($coordinate, $hemisphere) {
        if (is_string($coordinate)) {
            $coordinate = array_map("trim", explode(",", $coordinate));
        }
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
            $coordinate[$i] = $part[0];
            } else if (count($part) == 2) {
            $coordinate[$i] = floatval($part[0])/floatval($part[1]);
            } else {
            $coordinate[$i] = 0;
            }
        }
        list($degrees, $minutes, $seconds) = $coordinate;
        $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
        return $sign * ($degrees + $minutes/60 + $seconds/3600);
    }

}

?>
