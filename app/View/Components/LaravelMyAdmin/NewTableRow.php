<?php

namespace App\View\Components\LaravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NewTableRow extends Component
{
    public $name;
    public $type;

    public function __construct($name="", $type="")
    {
        $this->name = $name;
        $this->type = $type;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.new-table-row');
    }
}
