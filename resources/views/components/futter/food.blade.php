<div class="h-full">
    <div class="food glass-card flex flex-row md:flex-col justify-start md:justify-center items-center h-full p-4 relative overflow-hidden" id="food_{{$food->id}}"> 
        
        <!-- Mobile: Image on left, Name on right -->
        <div class="overflow-hidden rounded-full w-20 h-20 md:w-56 md:h-56 shadow-2xl border-4 border-white/5 md:mb-6 shrink-0 md:mt-4 md:order-last">
            <img src='{{ str_starts_with($food->img, 'http') ? $food->img : url('storage/futter/'.$food->img) }}' 
                class="w-full h-full foodimg object-cover transform group-hover:scale-110 transition-transform duration-500" 
                data-html="true" 
                data-trigger="hover" 
                data-toggle="popover" 
                title="{{$food->name}}" 
                data-content="{!!$food->ingredients!!}"
                foodid="{{$food->id}}"
            >
        </div>

        <div class="flex-grow md:flex-none flex flex-col md:items-center w-full md:w-auto ml-4 md:ml-0 md:mb-5 z-10">
            <div class="food-name text-xl md:text-2xl font-bold tracking-tight text-white drop-shadow-md md:h-12 flex items-center">{{$food->name}}</div>
            
            <!-- Action Icons -->
            <div class="flex flex-row md:absolute md:top-4 space-x-3 md:space-x-0 md:justify-center items-center mt-3 md:mt-0 md:w-full">
                <!-- Check Icon -->
                <div class="check md:absolute md:right-4 text-gray-400">
                    <button class="bg-gray-800/80 backdrop-blur-md rounded-full p-3 shadow-lg border border-white/10 hover:border-teal-400 hover:text-teal-400 transform hover:-translate-y-1 transition-all duration-200 group-hover:bg-gray-800">
                        <i class="fa-solid fa-check text-lg"></i> 
                    </button>
                </div>
                <!-- Edit Icon -->
                <div class="check md:absolute md:left-4 text-gray-400">
                    <a href="{{url('futter/'.$food->id)}}" class="block bg-gray-800/80 backdrop-blur-md rounded-full p-3 shadow-lg border border-white/10 hover:border-emerald-400 hover:text-emerald-400 transform hover:-translate-y-1 transition-all duration-200 group-hover:bg-gray-800">
                        <i class="fa-solid fa-marker text-lg"></i>
                    </a> 
                </div>
            </div>
        </div>
    </div>        
</div>

