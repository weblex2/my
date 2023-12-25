@php 
    $picname  = explode("/", $pic->pic);
    $picname = $picname[count($picname)-1];
@endphp
<div id="{{$pic->id}}" class="w-full">
    <div id="file_{{$pic}}" class="p-4 bg-zinc-800 flex items-center relative">
        @if (in_array(strtoupper(substr($pic,-3)), ['MOV']))
            <video class="img w-[756px] rounded-xl shadow-xl" controls>
                <source src="{{ Storage::url("gallery/test/") }}{{$pic->pic}}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <img src="{{asset($pic->pic)}}" alt="Image" class="img w-[768px] rounded-xl shadow-xl">
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
    <div class="gallery-text p-4 block w-[768px] text-white">{!!$pic->GalleryText[0]->text!!}</div>
</div>