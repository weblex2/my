<x-blog.blog-layout>
   {{--  <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden  sm:rounded-lg">
                <div class="p-3 m-5">
                   <form name="createPost" action="{{route("blog.update")}}" method="POST"  class="blog">
                        @csrf
                        <div class="grid grid-cols-12 gap-4">
                            <input type="hidden" name="id" value="{{ $post->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth()->user()->id }}">
                            <div class="col-span-12">Titel: </div>
                            <div class="col-span-12">
                                <input type="text" name="title" value="{{$post->title}}">
                            </div>
                            <div class="cols-span-12">
                                Content:
                            </div>    
                            <div class="col-span-12">
                               <textarea name="content">{{ $post->content }}</textarea> 
                            </div>
                            <button type="submit" class="blog-button">Save</button>   
                   </form>
                </div>
            </div>
        </div>
    </div>
</x-blog.blog-layout>
