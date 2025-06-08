<?php
namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;


class SetupResourceCreator extends Component implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?string $resourceName = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('resourceName')
                ->label('Ressourcen-Name')
                ->required()
                ->minLength(3)
                ->placeholder('z. B. User, Product, Invoice'),
        ]);
    }

    public function createResource(): void
    {
        $data = $this->form->getState();
        $resourceName = trim($data['resourceName']);

        if (empty($resourceName)) {
            $this->addError('resourceName', 'Der Ressourcen-Name darf nicht leer sein.');
            return;
        }

        try {
            // Clear old output buffer
            Artisan::output();

            // Aufruf mit zusätzlicher Option
            $status = Artisan::call("make:custom-filament-resource", [
                'name' => $resourceName,
                '--model' => true,
                '--migration' => true,
            ]);

            $output = Artisan::output();

            if ($status === 0) {
                session()->flash('success', "✅ Ressource wurde erstellt:\n<pre>{$output}</pre>");
                $this->form->fill(); // Reset
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
        return view('livewire.setup-resource-creator');
    }
}
