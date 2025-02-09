<x-noppal>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-center text-gray-100 leading-tight">
        <a href="{{route('futter.index')}}">Home</a> 
        &nbsp;
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
</x-slot>
<div class="container mx-auto flex-auto pt-20">
    <div class="container futter">
        <div class="container mx-auto flex-auto items-justify text-center">
            @php
                $today = date('Y-m-d');
            @endphp
            <x-futter.calendar date={{$today}} :ft="$ft" :datesdb=$datesdb :dates=$dates />
            <div class="grid grid-cols-6 hidden md:inline-grid w-full mb-4 nobr items-justify text-center">
                @foreach ($futter as $f)
                     <x-futter.food :food="$f"/> 
                @endforeach
            </div>
            <div class="w-full mb-4 md:hidden inline-grid">
                @foreach ($futter as $f)
                     <div class="w-full">   
                     <x-futter.food-mobile :food="$f"/> 
                     </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Send ntfy message
    $('.check').click(function(){
        var foodname = $(this).closest('.food').text();
        var url = "{{ route('ntfy.index', ":foodname") }}";
        url = url.replace(':foodname', foodname);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                console.log("message sent.");
            },
            error: function( data) {
                console.log(data);
            }
        });
    });
</script>
</x-noppal>