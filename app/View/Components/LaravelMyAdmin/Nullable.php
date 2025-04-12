<?php

namespace App\View\Components\laravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Nullable extends Component
{
    public $selected;
    public $edit;
    public function __construct($selected, $edit)
    {
        $this->edit = $edit;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.nullable');
    }
}
