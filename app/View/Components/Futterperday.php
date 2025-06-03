<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Futterperday extends Component
{
    /**
     * Create a new component instance.
     */

    public $img;
    public $name;

    public function __construct($name, $img)
    {
        $this->name = $name;
        $this->img = $img;
        dump($img);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.futter.futterperday');
    }
}
