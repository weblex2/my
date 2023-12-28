<!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
<div class="nextBlogWrapper m-3 bg-zinc-700 p-10 rounded-xl relative">
    <h1> Du hast das Ende des Blogs erreicht. </h1>
    <div class="my-5 font font-extrabold text-orange-500">
        Was willst du als nächstes sehen?
    </div>
    <div id="next_gallery_content" class="mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5"> 
            @foreach ($alternativeBlogs as $blog)
                <a href="/travel-blog/show/{{$blog->code}}">
                    <div class="btn p-5 w-200 h-100 border border-orange-500 rounded-xl"> {{$blog->name}}</div>
                </a>
            @endforeach
        </div> 
    </div>   
    <a class="mr-2 mt-5 btn" href="/travel-blog"> <i class="mr-2 mt-1 fa-solid fa-earth-americas"></i> Back to the world</a>
</div>
<div class="h-[150px]"></div>

    