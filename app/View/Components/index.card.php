<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class index.card extends Component
{
    /**
     * Create a new component instance.
     */

    public $img;
    public $header;
    public $link;

    public function __construct($img, $header, $link)
    {
        $this->img = $img;
        $this->header = $header;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.index.card');
    }
}
