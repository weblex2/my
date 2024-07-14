<div>
    <div class="food justify-center items-center mb-5" id={{$food->id}}> 
        <div class="food-name mb-5">{{$food->name}}</div>
            <div class="relative flex justify-center items-center">
                <div class="check absolute top-0 ml-48 text-grey-500 hover:text-green-500"><i class="fa-solid fa-check"></i>
            </div>
            <img src='{{url('storage/futter/'.$food->img)}}' class="rounded-full w-52 h-52" data-html="true" data-trigger="hover" data-toggle="popover" title="Popover title" data-content="{!!$food->ingredients!!}">
        </div>
    </div>        
</div>