<?php

namespace App\View\Components\LaravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\LaravelMyAdminController;

class TableColumnsDropdown extends Component
{
    public $id;
    public $db;
    public $columns;
    public $selected;

    public function __construct($id, $selected=null)
    {
        $lma = new LaravelMyAdminController();
        $this->db = session('db');
        $this->table = session('table');
        $this->id = $id;
        $this->selected = $selected;
        $this->columns = $lma->getFieldsFromTable($this->db, $this->table);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.table-columns-dropdown');
    }
}
