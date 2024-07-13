<x-noppal>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-center text-gray-100 leading-tight">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
</x-slot>
<div class="container mx-auto flex-auto pt-20">
    <div class="container futter">
        <div class="container mx-auto flex-auto pt-20 items-justify text-center">
            <a href="{{route('futter.index')}}"><button class="btn mb-24">Home</button></a> 
            <div class="grid grid-cols-3 w-full mb-4 nobr items-justify text-center">
                @foreach ($futter as $f)
                    <div class="food justify-center items-center mb-5" id={{$f->id}}> 
                        <div class="mb-5">{{$f->name}}</div>
                        <div class="relative flex justify-center items-center">
                            <div class="check absolute top-0 right-28 text-grey-500 hover:text-green-500"><i class="fa-solid fa-check"></i></div>
                            <img src='{{url('storage/futter/'.$f->img)}}' class="rounded-full w-52 h-52">
                        </div>
                    </div>
                 @endforeach
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.check').click(function(){
        alert("Ho");
        $.ajax({
                type: 'GET',
                url: '{{route("ntfy.index"), ["msg" => "Hi2"]}}',
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