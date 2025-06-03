<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IndexCard extends Component
{
    /**
     * Create a new component instance.
     */

    public $img;
    public $header;
    public $text;
    public $link;

    public function __construct($img, $header, $text, $link)
    {
        $this->img = $img;
        $this->header = $header;
        $this->link = $link;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.index.card');
    }
}
