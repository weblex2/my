<!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
<div class="nextBlogWrapper bg-zinc-700 p-10 rounded-xl relative">
    <div class="absolute top-0 right-0 cursor-pointer m-3 shadow-xl" onclick="$('#nextBlog').css('visibility', 'hidden')"><i class="fa-solid fa-xmark text-orange-500"></i></div>
    <h1> You have reached the end of the Blog </h1>
    <div class="my-5 font font-extrabold text-orange-500">
        Where do you want to go next?
    </div>
    <div id="next_gallery_content" class="mb-5">
        <div class="grid grid-cols-3 gap-5"> 
            @foreach ($alternativeBlogs as $blog)
                <a href="/travel-blog/show/{{$blog->code}}">
                    <div class="p-5 w-200 h-100 border border-orange-500 rounded-xl"> {{$blog->name}}</div>
                </a>
            @endforeach
        </div> 
    </div>   
    <a class="mt-5" href="/travel-blog"> or go back to the world..</a>
</div>

    