<x-blog.blog-layout>
 <div class="py-12 h-full"> 
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full flex justify-center">
                <div class="pt-10  w-[80%] text-white">
                    @foreach ($kbs as $kb)
                    <div class="grid grid-cols-5 ">
                        <div>{{$kb->id}}</div>
                        <div><a href="{{route('knowledgeBase.show', $kb->id)}}"> {{$kb->topic}}</a></div>
                        <div>{{$kb->description}}</div>
                        <div>
                            <form method="POST" action="{{ route('knowledeBase.delete', ['id' => $kb->id] )}}"> 
                            @csrf
                            <input type="hidden" name="id" value="{{ $kb->id }}">
                            <button type="submit" class="cursor-pointer" ><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                        <div><a href="{{ route('knowledeBase.add') }}"><i class="fa-solid fa-plus"></i></a></div>

                    </div>
                    @endforeach 
                </div>  
            </div>
            <div class="flex justify-center">
                <div class="block">
                    {{ $kbs->links() }}
                </div> 
            </div>
            
        </div>
 </div>    

</x-blog.blog-layout>