@extends('layouts.clean')

@section('content')
<div class="absolute inset-0 flex items-center justify-center min-h-screen p-4 bg-green-700">
    <div class="table">

        <!-- Spieler oben -->
        <div class="absolute text-white transform -translate-x-1/2 top-4 left-1/2">
            Spieler Oben
        </div>

        <!-- Spieler links -->
        <div class="absolute top-1/2 left-4 transform -translate-y-1/2 text-white rotate-[-90deg]">
            Spieler Links
        </div>

        <!-- Spieler rechts -->
        <div class="absolute top-1/2 right-4 transform -translate-y-1/2 text-white rotate-[90deg]">
            Spieler Rechts
        </div>

        <!-- Tischmitte: gespielte Karten -->
        <div class="absolute flex gap-4 transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
            <div class="card">🂡</div>
            <div class="card">🃛</div>
            <div class="card">🃁</div>
            <div class="card">🂫</div>
        </div>

        <!-- Spieler unten + Handkarten -->
        <div class="absolute flex flex-col items-center transform -translate-x-1/2 bottom-4 left-1/2">
            <div class="mb-2 text-white">Du</div>
            <div class="flex gap-2">
                @php $i=0; @endphp
                @foreach($cards as $card)
                    @if ($card->player_id=='8')
                    @php $i++; @endphp
                    <div class="card card{{$i}}">
                        <img src="{{ asset('img/sk/'.$card->card_id.'.png') }}" />
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
