<?php

namespace App\View\Components\chat;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class message extends Component
{

    public $msg;
    public $user_id;
    public $channel;
    public $icon;
    /**
     * Create a new component instance.
     */
    public function __construct($message, $user_id, $icon='img/unknown.jpg', $channel='message')
    {
        $this->message = $message;
        $this->user_id = $user_id;
        $this->channel = $channel;
        $this->icon    = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat.message');
    }
}
