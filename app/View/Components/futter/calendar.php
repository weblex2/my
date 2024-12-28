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
    public $dates=[];
    public $datesdb=[];
    public $ft;
    

    public function __construct($date, $ft, $datesdb, $dates)
    {   
        $this->ft = $ft;
        $this->dates = $dates;
        $this->datesdb = $datesdb;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.futter.calendar');
    }
}
