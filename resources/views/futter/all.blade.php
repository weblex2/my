<x-noppal>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-center text-gray-100 leading-tight">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
</x-slot>
<div class="container mx-auto flex-auto pt-20">
<div class="container futter">
    {{-- @foreach($futter as $f)
        <div class="grid grid-col-1 w-full mb-4 nobr">
            <div class="text-center items-justify"><h1>{{$f->name}}</h1></div>
            <div class="flex justify-center items-center">
                <img src='{{url('storage/futter/'.$f->img)}}' class="rounded-full w-52 h-52 text-center">
            </div>
        </div>
    @endforeach --}}
    <div class="container mx-auto flex-auto pt-20 items-justify text-center">
        <div class="grid grid-cols-3 w-full mb-4 nobr items-justify text-center">
               @foreach ($futter as $f)
                    <div class="justify-center items-center mb-5"> 
                        <div class="mb-5">{{$f->name}}</div>
                        <div class="flex justify-center items-center">
                            <img src='{{url('storage/futter/'.$f->img)}}' class="rounded-full w-52 h-52">
                        </div>
                    </div>
                @endforeach
            
            
        </div>
    </div>
    
</div>
</div>
</x-noppal>