@php 
    $picname  = explode("/", $pic->pic);
    $picname = $picname[count($picname)-1];
    if (isset($pic->GalleryText[0]->text)){
        $gallery_text = $pic->GalleryText[0]->text;
    }
    else{
        $gallery_text = "No text available";
    }
@endphp
<div id="pic_{{$pic->id}}" class="w-full">
    <div id='file_{{$pic->id}}' class="p-4 bg-zinc-800 flex items-center relative">
        @if (in_array(strtoupper(substr($picname,-3)), ['MOV']))
            <video class="img w-[756px] rounded-xl shadow-xl" controls>
                <source src="{{$pic->GalleryPicContent->filecontent}}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <a href="javascript:void(0)" onclick="showBigPic({{$pic->id}})">
                @if ($pic->GalleryPicContent->filecontent!="")
                <img src="{{$pic->GalleryPicContent->filecontent}}" alt="Image" class="img">
                @else
                    Sorry pic is damaged.
                @endif    
            </a>
        @endif   
    </div>
    <div id="exif_{{$pic->id}}" class="hidden gallery-meta h-20 overflow-auto text-sm">
        Lat : {{ $pic->lat }}    <br>
        Lon : {{ $pic->lon }}    <br>
        
        @isset ($pic->meta['error'])
            <div> Meta error</div>
        @else
            <pre>
            @php 
                print_r($pic->meta);        
            @endphp
            </pre>    
        @endisset    
            
        {{-- @foreach ($pic->meta as $key => $value)
            <div> {{$key}} => {{$value}}</div>   
        @endforeach --}}
    </div>
    <div class="flex justify-between">
        <div class="p-4 text-xs font-extrabold">
            {{$picname}}
        </div>
        <div class="p-4 text-xs font-extrabold">
            @if (Auth::user())
                <a href="{{route('gallery.editPic', ['pic_id' => $pic->id])}}"><i class="fas mr-1 fa-edit gallery-edit-icon"></i></a>
                <a href="javascript:void(0)" onclick="showDeletePopup({{$pic->id}})"><i class="deleteBlog mr-1 fa-solid fa-trash gallery-delete-icon"></i></a>
            @endif 
            @if ($pic->lat!="" && $pic->lon!="")
            <a href="https://www.google.com/maps?q={{$pic->lat}},{{$pic->lon}}" target="_blank">    
                <i class="gallery-comment-icon gallyery-google-maps-link fa-solid fa-globe mr-1" title="Google Maps"></i>
            </a>
            @else
                <i class="gallery-comment-icon fa-solid fa-globe mr-1" title="Google Maps"></i>
            @endif
            <i class="gallery-comment-icon fa-solid fa-camera mr-1" title="Exif Data" onclick="$('#exif_{{$pic->id}}').toggle()"></i>
            <i class="gallery-comment-icon fa fa-comment mt-[1px] mr-1" title="Comment"></i>
            <i class="gallery-comment-icon fa fa-thumbs-up" aria-hidden="true" title="Like!"></i>     
            <i class="gallery-comment-likes" aria-hidden="true">5</i>  
                  
        </div>
    </div>
    <div class="gallery-text p-4 block full text-white">{!!$gallery_text!!}</div>
    <script>
        function showBigPic(id){
            $('#bigPic').hide();
            $('#loaderBigPic').show();
            $('#bigPicModal').css('visibility','visible');
            $.ajax({
                type: "GET",
                url: "/travel-blog/getBigPic/"+id,
                success: function(data){
                    $('#bigPic').html('<img class="p-10 max-h-screen" src="' + data.data + '">');
                    $('#bigPic').show();
                    $('#loaderBigPic').hide();
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }

            });
        }
    </script>
</div>