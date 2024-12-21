<?php

namespace App\View\Components\futter;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;

class calendar extends Component
{
    /**
     * Create a new component instance.
     */
    public $date;
    public $startDate;
    public $dates=[];
    public $datesdb=[];
    public $ft;
    

    public function __construct($date, $ft, $datesdb=[])
    {   
        dump($date);
        if (strlen($date)<3){
            echo "Invalid";
        }
        $this->ft = $ft;
        
        $startDate = Carbon::createFromFormat('Y-m-d', $date);
        $startDateDb = Carbon::createFromFormat('Y-m-d', $date);
        echo $startDateDb;
        $this->datesdb = $datesdb;
        $this->dates[] = $startDate->format('l d.m.y'); 
        /* $this->datesdb[] = $startDateDb->format('Y-m-d'); 
        for ($i=0; $i<6; $i++){
            $this->dates[] = $startDate->addDays(1)->format('l d.m.y');
            $this->datesdb[] = $startDateDb->addDays(1)->format('Y-m-d');
        } */
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.futter.calendar');
    }
}
