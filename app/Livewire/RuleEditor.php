<?php

namespace App\Livewire;

use Filament\Forms;
use Livewire\Component;

class RuleEditor extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public array $rule = [
        'conditions' => [
            'type' => 'and',
            'children' => [],
        ],
        'actions' => [],
    ];

    public function mount()
    {
        $this->form->fill([
            'rule_json' => json_encode($this->rule, JSON_PRETTY_PRINT),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('rule_json')
                ->label('Regel (JSON)')
                ->rows(20)
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Versuche JSON zu parsen und in $rule als Array zu speichern
                    $decoded = json_decode($state, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->rule = $decoded;
                    }
                }),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();
        $decoded = json_decode($data['rule_json'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addError('rule_json', 'Ungültiges JSON.');
            return;
        }

        $this->rule = $decoded;

        // Hier kannst du $this->rule speichern oder weiterverarbeiten
        session()->flash('success', 'Regel gespeichert!');
    }

    public function render()
    {
        return view('livewire.rule-editor', [
            'rule' => $this->rule,
        ]);
    }
}
