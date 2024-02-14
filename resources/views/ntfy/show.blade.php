<x-blog.blog-layout>
       <div class="py-12 h-full">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full p-10">
                <div class="grid grid-cols-12 text-white">
                  <div class="col-span-4 font-extrabold">Topic</div>
                  <div class="col-span-3 font-extrabold">Date</div>
                  <div class="col-span-2 font-extrabold">Reminder</div>
                  <div class="col-span-2 font-extrabold">Recurring_interval</div> 
                  <div class="col-span-1">Delete</div>
                   @foreach($notifications as $notification)
                        <div class="col-span-4">{{$notification->topic}}</div>
                        <div class="col-span-3">{{$notification->date}}</div>
                        <div class="col-span-2">{{$notification->reminder}}</div>
                        <div class="col-span-2">{{$notification->recurring_interval}}</div>
                        <div class="col-span-1 text-green-300">Delete</div>
                   @endforeach
                </div>

            </div>
        </div>
    </div>
    <script>
        
    </script>
</x-blog.blog-layout>
