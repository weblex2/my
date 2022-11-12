<div class="blog">
    <div class="header">
        <div class="mb-3"> 
            <img src="{{ $post->user->profile_photo_url }}" class="avatar">
            <div class="blog_created_at">{{ $post->user->name }} {{ $post->created_at }}  </div>
        </div>
        <div>    
            <h1 class="">
                {{ $post->title }} 

                @if (Auth()->check() && Auth()->user()->id == $post->user_id) 
                    <a class="edit-post" href="{{ route("blog.edit", ['id'=> $post->id] )}}">edit</a>
                @endif

            </h1>
        </div>
    </div>
    <div class="blog_content">
            {!! nl2br($post->content) !!}
    </div>
       
</div>