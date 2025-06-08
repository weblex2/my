<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SetupResourceCreator extends Component implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?string $resourceName = '';
    public array $resources = [];

    public function mount(): void
    {
        $this->form->fill();
        $this->loadResources();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2) // 2 Spalten für das Formular
                    ->schema([
                        Forms\Components\TextInput::make('resourceName')
                            ->label('Ressourcen-Name')
                            ->required()
                            ->minLength(3)
                            ->placeholder('z. B. User, Product, Invoice')
                            ->columnSpan(2), // Überspannt beide Spalten
                    ]),
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

        $this->resources = collect($files)
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->filter(fn ($name) => str_ends_with($name, 'Resource'))
            ->map(fn ($name) => str_replace('Resource', '', $name))
            ->values()
            ->toArray();
    }

    public function createResource(): void
    {
        $data = $this->form->getState();
        $resourceName = Str::studly($data['resourceName']) . 'Resource';

        if (empty($resourceName)) {
            $this->addError('resourceName', 'Der Ressourcen-Name darf nicht leer sein.');
            return;
        }

        try {
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
        ]);
    }
}
