<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GalleryItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $pic;
    public $content;
     
    public function __construct($pic, $content)
    {
        $this->pic = $pic;
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.gallery-item');
    }
}
