<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class FileUploadController extends Controller
{
    public function index()
    {
        return view('blog.upload');
    }
 
    public function uploadToServer(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);
 
       $name = time().'.'.request()->file->getClientOriginalExtension();
  
       $request->file->move(public_path('uploads'), $name);
 
       #$file = new FileUpload;
       #$file->name = $name;
       #$file->save();
  
        return response()->json(['success'=>'Successfully uploaded.']);
    }

    public  function FileUpload(Request $request)
    {
        $image = $request->file('file');
        $imageName  = $image->getClientOriginalName();
        //$imageName = time().'.'.$image->extension();
        
        $image->move(public_path('images/tmp'),$imageName);
        return response()->json(['success'=>$imageName]);
    }
}
