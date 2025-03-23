<?php

namespace App\View\Components\laravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\PhpMyAdminController;

class sidebar extends Component
{

    public $dbs;

    public function __construct()
    {
        $pac = new PhpMyAdminController();
        $this->dbs = $pac->getDatabases();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.sidebar');
    }
}
