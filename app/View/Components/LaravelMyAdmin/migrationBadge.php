<?php

namespace App\View\Components\laravelMyAdmin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\LaravelMyAdminController;

class MigrationBadge extends Component
{
    public $count;

    public function __construct()
    {
        $this->count = LaravelMyAdminController::getNewMigrationCount() ?? 0;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laravel-my-admin.migration-badge');
    }
}
