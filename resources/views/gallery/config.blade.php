<x-gallery-layout>
    <div id="debug" class="hidden fixed top-0 left-0 bg-gray-900 p-10 z-10 text-white">debug</div>
    <div id="debug2" class="hidden fixed top-0 right-0 bg-gray-900 p-10 z-10 text-white">debug2</div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            <a href="/travel-blog">{{ __('Blog') }}</a> / Configuration
        </h2>
    </x-slot>
    <div id="config" class=" w-full h-[872px] bg-zinc-800">

            @if ($message = Session::get('success'))
                <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                    <strong>{{ $message }}</strong>
                </div>
            @elseif (($message = Session::get('error')))
                <div class="text-center p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
                    <strong>{{ $message }}</strong>
                </div>
            @endif    
            <div class="flex items_center w-[20%] mt-10">
            <div class="grid grid-cols-2">
            @foreach ($config as $conf)
                <div class="p-2">{{$conf->option}}</div>
                <div><input class="text-right" type="text" name={{$conf->option}} value={{$conf->value}} /></div>
            @endforeach
            </div>
            </div>
    </div>  
    

    <script>
        function saveConfig(){
            var id = $('#delete_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                type: 'POST',
                url: '/gallery/deletepic',
                data: {
                    item_id: id,
                },
                async: false,
                success: function (data){
                    console.log("Data " + id+ " deleted");
                    $('#'+id).remove();
                    closeDeletePopup();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        
        }
    </script>    
</x-gallery-layout>