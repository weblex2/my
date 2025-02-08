<div>
    <div class="food justify-center items-center mb-5" id="food_{{$food->id}}"> 
        <div class="food-name text-2xl h-3 mb-5">{{$food->name}}</div>
            <div class="relative flex justify-center items-center">
                <div class="check absolute top-0 ml-48 text-grey-500 ">
                    <i class="fa-solid fa-check rounded-full p-2 hover:cursor-pointer hover:text-green-500 hover:bg-gray-500"></i> 
                </div>
                <div class="check absolute  ml-48 bottom-0 text-grey-500 ">
                    <a href="{{url('futter/'.$food->id)}}"><i class="fa-solid fa-marker rounded-full p-2 hover:cursor-pointer hover:text-green-500 hover:bg-gray-500"></i></a> 
                </div>
            <img src='{{url('storage/futter/'.$food->img)}}' 
                class="rounded-full w-52 h-52 foodimg" 
                data-html="true" 
                data-trigger="hover" 
                data-toggle="popover" 
                title="{{$food->name}}" 
                data-content="{!!$food->ingredients!!}"
                foodid="{{$food->id}}"
            >
        </div>
    </div>        
</div>

