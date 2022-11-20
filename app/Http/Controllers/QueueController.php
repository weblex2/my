<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Mail\DemoMail;
use App\Models\Jobs;


class QueueController extends Controller
{
    //use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function index(){
        return view('queue.index');
    }

    public function createJob(){
        $details['email'] = 'alex@noppenberger.org';
        $details['title'] = 'alex@noppenberger.org';
        $details['body'] = 'alex@noppenberger.org';
        $job  = new SendEmailJob($details);
        $this->dispatch($job);
        return redirect()->route('showJobs');
    }

    public function showJobs(){
        $jobs = Jobs::all();
        return view('queue.jobs', compact('jobs'));
    }
}
