<?php

namespace App\View\Components\LaravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\LaravelMyAdminController;

class Sidebar extends Component
{
    public $dbs;
    public $selected_db;


    public function __construct()
    {
        $pac = new LaravelMyAdminController();
        $this->dbs = $pac->getDatabases();
        $this->selected_db = session('db');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.sidebar');
    }
}
