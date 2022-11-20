<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Mail;
use App\Mail\DemoMail;
  
class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index($mailData=[])
    {
        if (!isset($mailData['title']) || $mailData['title']=="") {
            $mailData['title'] = "Test";
        }
        if (!isset($mailData['body']) || $mailData['body']=="") {
            $mailData['body'] = "This is for testing email using smtp.";
        }

        Mail::to('alex@noppenberger.org')->send(new DemoMail($mailData));
    }
}