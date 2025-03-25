<div class="p-4">
    <h1 class="text-2xl font-semibold">Datenbanken</h1>

    @foreach($databases as $db)
        <div class="my-4">
            <!-- Datenbank Button -->
            <button class="w-full p-2 text-white bg-blue-500 rounded" wire:click="toggleDatabase('{{ $db['name'] }}')">
                {{ $db['name'] }}
            </button>

            <!-- Tabellen ausklappen, wenn in $expandedDatabases -->
            <div class="ml-4" x-show="expandedDatabases.includes('{{ $db['name'] }}')" x-transition>
                @foreach($db['tables'] as $table)
                    <div class="my-2">
                        <!-- Tabellen Button -->
                        <button class="w-full p-2 text-black bg-gray-300 rounded" wire:click="toggleTable('{{ $db['name'] }}', '{{ $table['table'] }}')">
                            {{ $table['table'] }}
                        </button>

                        <!-- Spalten ausklappen -->
                        <div class="ml-4" x-show="expandedDatabases.includes('{{ $db['name'] }}-{{ $table['table'] }}')" x-transition>
                            <ul>
                                @foreach($table['columns'] as $column)
                                    <li>{{ $column['Field'] }} ({{ $column['Type'] }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
