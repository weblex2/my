<div id="blog-{{$post->id}}" class="blog" blog_id="{{$post->id}}">
    
        <div class="grid grid-cols-12 gap-2 pb-3">
            {{-- // row 1 --}}
            <div class="col-span-11 header">
                <img src="{{ $post->user->profile_photo_url }}" class="avatar">
                <div class="text-align-left">{{ $post->user->name }}</div> 
            </div>    
            <div class="col-span-1">
                <div class="text-right font-bold text-xs">{{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y')}}</div>   
            </div>

            {{-- // row 2 --}}
            <div class="col-span-12">    
                <h1 class="">
                    {{ $post->title }} 
                </h1>
            </div>
          
            <div class="col-span-12 blog_content">
                {!! nl2br($post->content) !!}
            </div>
            
            <div class="col-span-12">
                <a class="comment-post" 
                        href="javascript:void(0)">
                        <i class="fa-regular fa-comment text-xl" title="make a comment"></i>
                    </a>
                @if (Auth()->check() && Auth()->user()->id == $post->user_id) 
                    <a class="edit-post" 
                        href="{{ route("blog.edit", ['id'=> $post->id] )}}">
                        <i class="fa-regular fa-edit text-xl" title="edit post"></i>
                    </a>
                    <a class="delete-post" 
                        href="javascript:void(0)"
                        onclick="$('#frmBlogDelete_{{ $post->id }}').submit()">
                        <i class="fa-regular fa-trash-can text-xl" title="delete post"></i>    
                    </a>
                    <form id="frmBlogDelete_{{ $post->id }}" method="POST" action="{{ route("blog.delete") }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$post->id}}">
                    </form>        
                @endif

            </div> 
        </div>
        <div class="blog-comments"> 
            @foreach ($post->comments as $comment)
            <div class="blog-comment">
                <div class="blog-comment-header">
                    {{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y')}} 
                    {{ $comment->comment_user->name }}</div>
                <div class="blog-comment-body">{{ $comment->comment }}</div> 
            </div>    
            @endforeach
        </div>    
</div>