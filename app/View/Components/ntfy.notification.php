<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ntfy.notification extends Component
{
    public $mode;
    public $notification;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($notification, $mode)
    {
        $this->mode = $mode;
        $this->notification = $notification;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ntfy.notification');
    }
}
