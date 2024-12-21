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
            <x-futter.calendar date={{$today}} :ft="$ft" />
            <div class="grid grid-cols-3 w-full mb-4 nobr items-justify text-center">
                @foreach ($futter as $f)
                    {{-- <div class="food justify-center items-center mb-5" id={{$f->id}}> 
                        <div class="food-name mb-5">{{$f->name}}</div>
                        <div class="relative flex justify-center items-center">
                            <div class="check absolute top-0 right-28 text-grey-500 hover:text-green-500"><i class="fa-solid fa-check"></i></div>
                            <img src='{{url('storage/futter/'.$f->img)}}' class="rounded-full w-52 h-52">
                        </div>
                    </div> --}}
                     <x-futter.food :food="$f" /> 
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