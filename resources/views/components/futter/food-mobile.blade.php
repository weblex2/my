<div class="food-mobile" id="food_{{$food->id}}">
    <div class="w-16">
        <img src='{{url('storage/futter/'.$food->img)}}' 
                    class="rounded-full w-8 h-8 foodimg" 
                    data-html="true" 
                    data-trigger="hover" 
                    data-toggle="popover" 
                    title="{{$food->name}}" 
                    data-content="{!!$food->ingredients!!}"
                    foodid="{{$food->id}}"
        >
    </div>
    <div class="food-mobile-text">{{$food->name}}</div>
</div>                

