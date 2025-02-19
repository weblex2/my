<?php

namespace App\View\Components\futter;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class food extends Component
{
    /**
     * Create a new component instance.
     */
    public $food;

    public function __construct($food, $all=false)
    {
        $this->food = $food;
        $this->all = $all;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.futter.food');
    }
}
