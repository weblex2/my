<?php

namespace App\View\Components;

use Illuminate\View\Component;

class gallery.showAlternativeBlog extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $alternativeBlogs;

    public function __construct($alternativeBlogs)
    {
        $this->alternativeBlogs = $alternativeBlogs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.gallery.show-alternative-blog');
    }
}
