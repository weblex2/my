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
            <div class="col-span-3">
                <div class="justify-center items-center"> 
                    <div class="justify-center items-center"> 
                    <x-futter.food :food="$futter[0]" />
                </div>
                </div>   
                
            </div>
            <div class="">
                <div class="justify-center items-center"> 
                    <x-futter.food :food="$futter[1]" />
                </div>

            </div>
            <div class="">&nbsp;</div>
            <div class="">
                <div class="justify-center items-center"> 
                    <div class="justify-center items-center"> 
                    <x-futter.food :food="$futter[2]" />
                </div>
                </div>
            </div>
            <div class="col-span-3"><a href="futter"><button class="btn">Nööö, gib mir mehr Vorschläge...</button></a></div>
            <div class="col-span-3">&nbsp;</div>
            <div class="col-span-3"><a href="futter/all"><button class="btn">Ach was solls.., ich will alles sehen!!</button></a></div>
            <div class="col-span-3">&nbsp;</div>
            <div class="col-span-3"><a href="futter/new"><button class="btn">Yeah - Hab was Neues!!!</button></a></div>
        </div>
    </div>
    
</div>
</div>
<script type="text/javascript">
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