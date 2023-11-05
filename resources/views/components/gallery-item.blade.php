<div class="p-5 w-fit">
    <div id="file_{{$pic}}" class="p-4 bg-zinc-900 flex items-center ">
        <img src="{{ Storage::url("gallery/test/") }}{{$pic}}" alt="Image" class="img w-[756px] rounded-xl shadow-xl">
    </div>
    <div class="p-4 text-xs font-extrabold">{{$pic}}</div>
    <div class="p-4 text-white">{{$content}}</div>
</div>