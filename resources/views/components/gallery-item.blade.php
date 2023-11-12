<div class="p-5 w-fit">
    <div class="p-4 flex justify-between">
        <a href=""><i class="fas fa-edit gallery-edit-icon"></i> Edit</a>
        <a href=""><i class="fa-solid fa-trash gallery-delete-icon"></i> Delete</a>
    </div>    
    <div id="file_{{$pic}}" class="p-4 bg-zinc-900 flex items-center ">
        <img src="{{ Storage::url("gallery/test/") }}{{$pic}}" alt="Image" class="img w-[756px] rounded-xl shadow-xl">
    </div>
    <div class="flex justify-between">
        <div class="p-4 text-xs font-extrabold">
            {{$pic}}
        </div>
        <div class="p-4 text-xs font-extrabold">
            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
        </div>
    </div>
    <div class="p-4 block w-fit w-[756px] text-white">{{$content}}</div>
</div>