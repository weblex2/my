@php 
    $picname  = explode("/", $pic->pic);
    $picname = $picname[count($picname)-1];
@endphp
<div class="p-5 w-fit">
    <div class="p-4 flex justify-between">
        <a href=""><i class="fas fa-edit gallery-edit-icon"></i> Edit</a>
        <a href=""><i class="fa-solid fa-trash gallery-delete-icon"></i> Delete</a>
    </div>    
    <div id="file_{{$pic}}" class="p-4 bg-zinc-900 flex items-center ">
        @if (in_array(strtoupper(substr($pic,-3)), ['MOV']))
            <video class="img w-[756px] rounded-xl shadow-xl" controls>
                <source src="{{ Storage::url("gallery/test/") }}{{$pic->pic}}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <img src="{{asset($pic->pic)}}" alt="Image" class="img w-[756px] rounded-xl shadow-xl">
        @endif   
    </div>
    <div class="flex justify-between">
        <div class="p-4 text-xs font-extrabold">
            {{$picname}}
        </div>
        <div class="p-4 text-xs font-extrabold">
            <i class="gallery-comment-icon fa fa-comment mt-[1px]"></i>&nbsp;
            <i class="gallery-comment-icon fa fa-thumbs-up" aria-hidden="true"></i>            
        </div>
    </div>
    <div class="p-4 block w-[756px] text-white">{!!$content!!}</div>
</div>