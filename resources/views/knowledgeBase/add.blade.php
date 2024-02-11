<x-knowledgeBase.kb-layout>
 <div class="py-12 h-full"> 
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full flex justify-center">
                <div class="pt-10  w-[80%] text-black">
                    <form method="POST" action="{{ route('knowledeBase.store')}}"> 
                        @csrf
                        <div class="grid grid-cols-12">
                        <div>Topic</div><div class="col-span-11"><input type="text" name="topic"></div>
                        <div>Description</div><div class="col-span-11"><input type="text" name="description"></div>
                        <div>Text</div>
                        <div class="col-span-11">
                            <textarea name="text"></textarea>
                        <div>
                        
                        <div class="col-span-2">
                            <button type="submit" class="cursor-pointer" >Add</button>
                        </div>
                        </div>
                    </form>    
                </div>
                </div>
                  
            </div>
            
        </div>
 </div>    

</x-knowledgeBase.kb-layout>