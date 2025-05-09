<?php

namespace App\View\Components\LaravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DefaultDropdown extends Component
{
    public $selected;

    public function __construct($selected=null)
    {
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.default-dropdown');
    }
}
