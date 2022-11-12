<div class="blog">
    <div class="header">
        <img src="{{ $post->user->profile_photo_url }}" class="avatar">
        <h1 class="text-slate-500 font-extrabold">
            {{ $post->title }} 

            @if (Auth()->check() && Auth()->user()->id == $post->user_id) 
                <a class="edit-post" href="{{ route("blog.edit", ['id'=> $post->id] )}}">edit</a>
            @endif

        </h1>
        
    </div>
    <div class="blog_content">
            {!! nl2br($post->content) !!}
    </div>
    <div class="blog_created_at">{{ $post->created_at }} {{ $post->user->email }} </div>   
</div>