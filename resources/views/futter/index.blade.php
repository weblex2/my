<x-noppal>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-center text-gray-100 leading-tight">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
</x-slot>
<div class="container mx-auto flex-auto pt-20">
@php
    $today = date('Y-m-d');
@endphp
<x-futter.calendar  date={{$today}} :ft="$ft" :datesdb=$datesdb :dates=$dates />
<div class="container futter">
    <div class="container mx-auto flex-auto pt-20 items-justify text-center">
        <!-- Desktops -->
        <div class="container-desktop">
            <div class="col-span-1 md:col-span-3">
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
            <div class="pt-10">
                <div class="col-span-3">
                    <a href="futter">
                        <button class="text-xl btn border"><i class="fa fa-refresh" aria-hidden="true"></i> &nbsp; Nööö, gib mir mehr Vorschläge...</button>
                    </a>
                </div>
                <div class="col-span-3">&nbsp;</div>
                <div class="col-span-3">
                    <a href="futter/all">
                        <button class="text-xl btn border"><i class="fa-solid fa-globe"></i> &nbsp; Ach was solls.., ich will alles sehen!!</button>
                    </a>
                </div>
                <div class="col-span-3">&nbsp;</div>
                <div class="col-span-3">
                    <a href="futter/new">
                        <button class="text-xl btn border"><i class="fa-solid fa-plus"></i> &nbsp; Yeah - Hab was Neues!!!</button>
                    </a>
                </div>
      
            </div>
            <div class="">
                <div class="justify-center items-center"> 
                    <div class="justify-center items-center"> 
                    <x-futter.food :food="$futter[2]" />
                </div>
                </div>
            </div>
        </div>

        <!-- mobile -->
        
        <div class="container-mobile"> 
            <x-futter.food-mobile :food="$futter[0]" />
            <x-futter.food-mobile :food="$futter[1]" />
            <x-futter.food-mobile :food="$futter[2]" />
            <div>
            <a href="futter">
                <button class="text-xl btn border"><i class="fa fa-refresh" aria-hidden="true"></i> &nbsp; Nööö, gib mir mehr Vorschläge...</button>
            </a>
            </div>
            <div>
                <a href="futter/all">
                    <button class="text-xl btn border"><i class="fa-solid fa-globe"></i> &nbsp; Ach was solls.., ich will alles sehen!!</button>
                </a>
            </div>
            <div>
                <a href="futter/new">
                    <button class="text-xl btn border"><i class="fa-solid fa-plus"></i> &nbsp; Yeah - Hab was Neues!!!</button>
                </a>
            </div>

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