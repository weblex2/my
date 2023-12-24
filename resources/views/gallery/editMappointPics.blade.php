<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            {{ __('Gallery / Edit Map Points') }} / {{$mp->mappoint_name}}
        </h2>
    </x-slot>
    <div class="py-12 h-screen overflow-auto">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 flex justify-center ">

            <ul id="sortable">
                @foreach ($mp->GalleryPics as $i => $pic)
                    <li>    
                        <div class="portlet-header"><img id="{{$pic->id}}" src="{{asset($pic->pic)}}" class="w-25 h-25"></div>
                    </li>
                @endforeach
            </ul>

        </div>
        <button class="btn-submit" id="save">save</button>

    </div>
    
    
    <script>
        $( "#sortable" ).sortable();
        $( "#sortable" ).disableSelection();
        $('#save').click(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = [];
            var row = [];
            $('#sortable li').each(function(index, element){
                var id =  $(element).find('img').attr('id');
                row = {'id' : id, 'ord' : (index+1)};
                data[index] = row;
            });    
            data = JSON.stringify(data);
            console.log(data);
            
            $.ajax({
                url: '/travel-blog/updatePicOrder',
                type: 'POST',
                dataType: 'json',
                data: {'data': data},
                success: function(resp){
                    console.log(resp);
                },
                error: function(resp){
                    console.log('error');
                }
            });
        });
    </script>
</x-gallery-layout>
