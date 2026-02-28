<div class="calendar-mobile space-y-4">
    <div class="text-sm text-gray-400 font-semibold mb-2 uppercase tracking-wider text-center">Dein Speiseplan</div>
    @for ($i = 0; $i < 7; $i++)
        <div class="glass-card flex items-center p-4">
            <div class="flex-grow">
                <div class="text-xs text-teal-400 font-bold mb-1">{{$dates[$i]}}</div>
                <div class="day-mobile-dropzone text-gray-300 text-sm font-medium h-8 w-full rounded border border-dashed border-gray-600 flex items-center px-2" date={{$datesdb[$i]}}>
                    @if(!isset($ft[$datesdb[$i]]['img']))
                        <span class="text-gray-500 text-xs italic">Nichts geplant</span>
                    @else
                       <span class="text-white text-xs whitespace-nowrap overflow-hidden text-ellipsis">{{$ft[$datesdb[$i]]['name'] ?? 'Geplantes Essen'}}</span>
                    @endif
                </div>
            </div>
            
            <div class="ml-4 shrink-0 w-16 h-16 rounded-lg overflow-hidden border border-white/10 shadow-lg bg-black/40">
                @isset($ft[$datesdb[$i]]['img'])
                    <img src="{{$ft[$datesdb[$i]]['img']}}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                @endisset
            </div>
        </div>
    @endfor
</div>