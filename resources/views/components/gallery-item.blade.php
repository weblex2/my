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
<div id="{{$pic->id}}" class="w-full">
    <div id="file_{{$pic}}" class="p-4 bg-zinc-800 flex items-center relative">
        @if (in_array(strtoupper(substr($picname,-3)), ['MOV']))
            <video class="img w-[756px] rounded-xl shadow-xl" controls>
                <source src="{{$pic->GalleryPicContent->filecontent}}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <a href="javascript:void(0)" onclick="showBigPic({{$pic->id}})">
                <img src="{{$pic->GalleryPicContent->filecontent}}" alt="Image" class="img w-full rounded-xl shadow-xl">
            </a>
        @endif   
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
            <i class="gallery-comment-icon fa fa-comment mt-[1px]"></i>&nbsp;
            <i class="gallery-comment-icon fa fa-thumbs-up" aria-hidden="true"></i>     
                  
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
                    $('#bigPic').html('<img src="' + data.data + '">');
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