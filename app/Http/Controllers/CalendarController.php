<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
class CalendarController extends Controller
{
    public function index($year=null, $month=null){
        if ($year==null) {
            $year = date('Y');
        }
        if ($month==null){
            $month=date("m");
        }
        $date= strtotime($year."-". $month."-01");
        $prevpage= date('Y/m',strtotime("-1 month", $date));
        $nextpage= date('Y/m',strtotime("+1 month", $date));
        $events = Calendar::where('date', '>=', $date )->orderBy('date','ASC')->get();
        $ev  = [];
        foreach ($events as $event) {
            $ev[date('d.m.Y',strtotime($event->date))][] = $event->event;
        }
        return view('calendar.index',   compact('date', 'year', 'month', 'prevpage', 'nextpage', 'ev'));
    }


    public function store(Request $request){
        #dd($request->all());
        $calEvent  = Calendar::create($request->all());
        $calEvent->save();
    }
}
