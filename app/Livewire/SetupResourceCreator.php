<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SetupResourceCreator extends Component implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?string $resourceName = '';
    public array $resources = [];
    public array $navigationGroups = [];
    public ?string $navigation_group = null; // Hier speichern wir die ausgewählte oder neue Gruppe

    public function mount(): void
    {
        $this->form->fill();
        $this->loadResources();

        $this->navigationGroups = DB::table('resources')
            ->distinct()
            ->pluck('navigation_group')
            ->filter() // null/leer rausfiltern
            ->toArray();
        //dump($this->navigationGroups);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('selectedNavigationGroup')
                ->label('Navigation Group')
                ->options(fn () => array_combine($this->navigationGroups, $this->navigationGroups))
                ->nullable()
                ->reactive(),


        ]);
    }

    protected function loadResources(): void
    {
        $resourcePath = app_path('Filament/Resources');

        if (!File::exists($resourcePath)) {
            $this->resources = [];
            return;
        }

        $files = File::files($resourcePath);

        $resourceNames = collect($files)
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->filter(fn ($name) => str_ends_with($name, 'Resource'))
            ->map(fn ($name) => str_replace('Resource', '', $name))
            ->values()
            ->toArray();

        $dbResources = DB::table('resources')
            ->whereIn('resource', $resourceNames)
            ->pluck('navigation_group', 'resource'); // key = resource-Name!

        $this->resources = collect($resourceNames)->map(function ($name) use ($dbResources) {
            return [
                'name' => $name,
                'navigation_group' => $dbResources->get($name) ?? null,
            ];
        })->toArray();
        //dump($this->resources);
    }
    public function saveResource()
    {
        // Hole Form-Daten
        $data = $this->form->getState();

        // Rufe Controller-Helper auf, z.B.
        app(\App\Http\Controllers\FilamentHelper::class)->saveResource($data);

        // Optional: Erfolgsmeldung setzen, Formular zurücksetzen etc.
    }

    public function createResource(): void
    {
        $data = $this->form->getState();

        $resourceName = Str::studly($data['resourceName']) . 'Resource';
        $navigationGroup = $data['navigation_group'] ?? null;

        if (empty($resourceName)) {
            $this->addError('resourceName', 'Der Ressourcen-Name darf nicht leer sein.');
            return;
        }

        if (empty($navigationGroup)) {
            $this->addError('navigation_group', 'Die Navigation Group darf nicht leer sein.');
            return;
        }

        try {
            // Hier ggf. Navigation Group in DB speichern, wenn neu
            if (!in_array($navigationGroup, $this->navigationGroups)) {
                // Einfügen in DB (nur wenn noch nicht vorhanden)
                DB::table('resource')->insertOrIgnore([
                    'name' => str_replace('Resource', '', $resourceName),
                    'navigation_group' => $navigationGroup,
                ]);
                // Nach Insert nochmal navigationGroups neu laden
                $this->navigationGroups = DB::table('resources')
                    ->distinct()
                    ->pluck('navigation_group')
                    ->filter()
                    ->toArray();
            }

            Artisan::output();

            $status = Artisan::call("make:custom-filament-resource", [
                'name' => $resourceName,
                '--model' => true,
                '--migration' => true,
            ]);

            $output = Artisan::output();

            if ($status === 0) {
                session()->flash('success', "✅ Ressource wurde erstellt:\n<pre>{$output}</pre>");
                $this->form->fill();
                $this->loadResources(); // Aktualisiere die Ressourcenliste
            } else {
                $this->addError('resourceName', "❌ Fehler beim Erstellen:\n<pre>{$output}</pre>");
            }
        } catch (\Throwable $e) {
            $this->addError('resourceName', '⚠️ Ausnahme: ' . $e->getMessage());
            Log::error('Fehler beim Erstellen der Ressource: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.setup-resource-creator', [
            'resources' => $this->resources,
            'navigationGroups' => $this->navigationGroups,
        ]);
    }
}
