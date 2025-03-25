<?php

namespace App\Livewire;

use Livewire\Component;

class DatabaseExplorer extends Component
{
    public $databases = [];
    public $expandedDatabases = [];

    // Diese Methode lädt die Datenbanken und Tabellen
    public function mount()
    {
        $this->databases = $this->getDatabases();
    }

    public function getDatabases()
    {
        // Deine Logik, um Datenbanken, Tabellen und Felder zu holen.
        // Hier ist nur ein Beispiel
        return [
            [
                'name' => 'database1',
                'tables' => [
                    ['table' => 'users', 'columns' => [['Field' => 'id', 'Type' => 'int'], ['Field' => 'name', 'Type' => 'varchar']]],
                    ['table' => 'posts', 'columns' => [['Field' => 'id', 'Type' => 'int'], ['Field' => 'content', 'Type' => 'text']]]
                ]
            ]
        ];
    }

    // Diese Methode schaltet das Ein- oder Ausklappen einer Datenbank
    public function toggleDatabase($database)
    {
        if (in_array($database, $this->expandedDatabases)) {
            $this->expandedDatabases = array_diff($this->expandedDatabases, [$database]);
        } else {
            $this->expandedDatabases[] = $database;
        }
    }

    public function render()
    {
        return view('livewire.database-explorer');
    }
}
