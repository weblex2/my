<div class="blog">
    <div class="header">
        
        <div class="grid grid-cols-12 gap-2 pb-3">
            {{-- // row 1 --}}
            <div class="col-span-11">
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
                @if (Auth()->check() && Auth()->user()->id == $post->user_id) 
                    <a class="edit-post" 
                        href="{{ route("blog.edit", ['id'=> $post->id] )}}">
                        [edit]
                    </a>
                    <a class="delete-post" 
                        href="javascript:void(0)"
                        onclick="$('#frmBlogDelete_{{ $post->id }}').submit()">
                        [delete]    
                    </a>
                    <form id="frmBlogDelete_{{ $post->id }}" method="POST" action="{{ route("blog.delete") }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$post->id}}">
                    </form>        
                @endif
            </div> 

        </div>
              
    </div>
    
       
</div>