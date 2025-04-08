<div wire:poll.10s>
    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full">
        {{ isset($count) ? $count : 'Kein Wert' }}
    </span>
</div>
