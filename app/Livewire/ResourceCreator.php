<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ResourceCreator extends Component implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public array $formState = [];
    public array $navigationGroups = [];

    public function mount(): void
    {
        Log::info('ResourceCreator: Mount aufgerufen', [
            'class' => __CLASS__,
            'file' => __FILE__,
            'time' => now()->toDateTimeString(),
            'livewire_id' => $this->getId(),
        ]);

        try {
            $this->navigationGroups = \DB::table('resources')
                ->distinct()
                ->pluck('navigation_group')
                ->filter()
                ->values()
                ->toArray();
            Log::info('ResourceCreator: Navigation Groups geladen', [
                'navigationGroups' => $this->navigationGroups,
            ]);
        } catch (\Throwable $e) {
            Log::error('ResourceCreator: Fehler beim Laden der Navigation Groups', [
                'error' => $e->getMessage(),
            ]);
        }

        $this->formState = [];
    }

    public function getFormProperty(): Form
    {
        Log::info('ResourceCreator: getFormProperty aufgerufen', [
            'time' => now()->toDateTimeString(),
        ]);

        return $this->makeForm()
            ->schema([
                TextInput::make('resourceName')
                    ->label('Ressourcenname')
                    ->required()
                    ->placeholder('z. B. User')
                    ->rules(['string', 'max:255']),
                Select::make('navigation_group')
                    ->label('Navigationsgruppe')
                    ->options(array_combine($this->navigationGroups, $this->navigationGroups))
                    ->required()
                    ->placeholder('Wähle eine Gruppe'),
            ])
            ->statePath('formState');
    }

    public function createResource(): void
    {
        Log::info('ResourceCreator: createResource aufgerufen', [
            'formState' => $this->formState,
            'time' => now()->toDateTimeString(),
            'livewire_id' => $this->getId(),
        ]);

        $form = $this->getFormProperty();
        $data = $form->getState();

        $resourceName = Str::studly($data['resourceName']) . 'Resource';
        $navigationGroup = $data['navigation_group'];

        try {
            if (!in_array($navigationGroup, $this->navigationGroups)) {
                \DB::table('resources')->insertOrIgnore([
                    'resource' => str_replace('Resource', '', $resourceName),
                    'navigation_group' => $navigationGroup,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->navigationGroups[] = $navigationGroup;
                Log::info('ResourceCreator: Neue Navigation Group hinzugefügt', [
                    'navigationGroup' => $navigationGroup,
                ]);
            }

            $status = Artisan::call('make:custom-filament-resource', [
                'name' => $resourceName,
                '--model' => true,
                '--migration' => true,
            ]);

            $output = Artisan::output();

            if ($status === 0) {
                session()->flash('success', "✅ Ressource erfolgreich erstellt:\n<pre>$output</pre>");
                $this->formState = [];
                Log::info('ResourceCreator: Ressource erstellt', [
                    'resourceName' => $resourceName,
                    'output' => $output,
                ]);
            } else {
                session()->flash('error', "❌ Fehler beim Erstellen der Ressource:\n<pre>$output</pre>");
                Log::error('ResourceCreator: Fehler beim Erstellen der Ressource', [
                    'output' => $output,
                ]);
            }
        } catch (\Throwable $e) {
            session()->flash('error', '⚠️ Fehler: ' . $e->getMessage());
            Log::error('ResourceCreator: Ausnahme beim Erstellen der Ressource', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        Log::info('ResourceCreator: Render aufgerufen', [
            'time' => now()->toDateTimeString(),
            'livewire_id' => $this->getId(),
        ]);
        return view('livewire.resource-creator');
    }
}
