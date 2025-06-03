<?php

namespace App\View\Components\LaravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableStructure extends Component
{
    public $fields;
    public $edit;

    public function __construct($fields, $edit)
    {
        $this->fields = $fields;
        $this->edit = $edit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.table-structure');
    }
}
