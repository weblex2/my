<?php

namespace App\View\Components\LaravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;

class MainMenu extends Component
{
    public $db;
    public $server;

    public function __construct()
    {
        $this->db = session('db');
        $this->server = gethostbyname(gethostname());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.main-menu');
    }
}
