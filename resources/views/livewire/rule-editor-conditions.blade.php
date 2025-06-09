@php
    $isGroup = isset($node['type']);
    $pathPrefix = $path === '' ? '' : $path . '.';
@endphp

<div class="p-4 mb-2 border rounded bg-gray-50">
    @if ($isGroup)
        <div class="flex items-center mb-2 space-x-4">
            <label>Typ:</label>
            <select wire:change="setType('{{ $path }}', $event.target.value)" wire:model="{{ 'rule.conditions' . ($path ? '.' . $path : '') }}.type" class="px-2 py-1 border rounded">
                <option value="and">AND</option>
                <option value="or">OR</option>
            </select>

            @if($path !== '')
                <button wire:click="removeCondition('{{ $path }}')" class="ml-auto text-red-600">✕ Gruppe entfernen</button>
            @endif
        </div>

        <div class="ml-4">
            @foreach ($node['children'] as $i => $child)
                @if (isset($child['type']))
                    @include('livewire.rule-editor-conditions', ['node' => $child, 'path' => $pathPrefix . $i])
                @else
                    <div class="flex items-center mb-1 space-x-2">
                        <input type="text" wire:model.defer="{{ 'rule.conditions' . ($path ? '.' . $path : '') }}.children.{{ $i }}.field" placeholder="Feld" class="px-2 py-1 border rounded" />
                        <select wire:model.defer="{{ 'rule.conditions' . ($path ? '.' . $path : '') }}.children.{{ $i }}.operator" class="px-2 py-1 border rounded">
                            <option>=</option>
                            <option>!=</option>
                            <option>&lt;</option>
                            <option>&lt;=</option>
                            <option>&gt;</option>
                            <option>&gt;=</option>
                            <option>in</option>
                            <option>not in</option>
                            <option>contains</option>
                        </select>
                        <input type="text" wire:model.defer="{{ 'rule.conditions' . ($path ? '.' . $path : '') }}.children.{{ $i }}.value" placeholder="Wert" class="px-2 py-1 border rounded" />
                        <button wire:click="removeCondition('{{ $pathPrefix . $i }}')" class="px-2 text-red-600">✕</button>
                    </div>
                @endif
            @endforeach

            <div class="flex mt-2 space-x-2">
                <button wire:click="addCondition('{{ $path }}')" class="btn btn-sm btn-primary">+ Bedingung hinzufügen</button>
                <button wire:click="addGroup('{{ $path }}')" class="btn btn-sm btn-secondary">+ Gruppe hinzufügen</button>
            </div>
        </div>
    @else
        <!-- Blattknoten (sollte nie direkt hier sein) -->
        <div>Fehler: Ungültiger Knoten.</div>
    @endif
</div>
