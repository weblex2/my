<?php

namespace App\Livewire;

use Livewire\Component;
use App\Filament\Pages\GeneratePdf;

class GrapesJsEditor extends Component
{
    public $htmlContent = '';

    public function render()
    {
        return view('livewire.grapes-js-editor');
    }

    public function updateHtml($html)
    {
        $this->htmlContent = $html;
    }

    public function generatePdf()
    {
        if (!$this->htmlContent) {
            $this->dispatch('alert', ['message' => 'Kein Inhalt im Editor. Bitte füge Inhalte hinzu.']);
            return;
        }

        // Rufe die generate-Methode der Filament-Page auf
        $parent = $this->getParentComponent(GeneratePdf::class);
        if ($parent) {
            return $parent->generate($this->htmlContent);
        } else {
            $this->dispatch('alert', ['message' => 'Parent-Komponente nicht gefunden.']);
        }
    }

    protected function getParentComponent($class)
    {
        $component = $this;
        while ($component) {
            if ($component instanceof $class) {
                return $component;
            }
            $component = $component->getParent();
        }
        return null;
    }
}
