<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public string $h1;
    public string $h2;
    public string $text;
    public string $back;
    public string $icon;


    public function __construct($h1 = '', $h2 = '', $text = '', $back = '', $icon='dot')
    {
        $this->h1 = $h1;
        $this->h2 = $h2;
        $this->text = $text;
        $this->back = $back;
        $this->icon = $icon;
    }

    public function render(): View|Closure|string
    {
        return view('components.card');
    }
}
