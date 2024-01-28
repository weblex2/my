<x-knowledgebase.kb-layout>
 <div class="py-12 h-full"> 
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full flex justify-center">
                <div class="pt-10  w-[80%] text-black">
                    <form method="POST" action="{{ route('knowledeBase.store')}}"> 
                        @csrf
                        <div>Topic: <input type="text" name="topic"></div>
                        <div>Description <input type="text" name="description"></div>
                        <div>
                            Text: <textarea name="text"></textarea>
                        <div>
                        <div>
                            <button type="submit" class="cursor-pointer" >Add</button>
                        </div>
                    </form>    
                </div>
                </div>
                  
            </div>
            
        </div>
 </div>    

</x-knowledgebase.kb-layout>