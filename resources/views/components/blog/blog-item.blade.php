<div class="blog">
    <div class="header">
        <img src="{{ $post->user->profile_photo_url }}" class="avatar">
        <h1 class="text-slate-500 font-extrabold">
            {{ $post->title }} 
        </h1>
        @if (Auth()->check() && Auth()->user()->id == $post->user_id) 
                <a href="block/edit/{{$post->id}}">edit</a>
        @endif
    </div>
    <div class="blog_content">
            {!! $post->content !!}
    </div>
    <div class="blog_created_at">{{ $post->created_at }} {{ $post->user->email }} </div>   
</div>