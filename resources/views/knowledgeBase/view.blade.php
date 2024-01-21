<x-blog.blog-layout>
 <div class="py-12 h-full"> 
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full flex justify-center">
                <div class="pt-10  w-[80%] text-white">
                    
                    <div>{{$kb->topic}}</div>
                    <div>{{$kb->description}}</div>
                    <div>
                        <div class="w-full h-20 text-white">{!!$kb->text!!}</div>
                    <div>
                    <div>
                        <form method="POST" action="{{ route('knowledeBase.delete')}}"> 
                            @csrf
                            <input type="hidden" name="id" value="{{ $kb->id }}">
                            <button type="submit" class="cursor-pointer" >löschen</button>
                        </form>
                    </div>
                </div>
                </div>
                  
            </div>
            
        </div>
 </div>    

</x-blog.blog-layout>