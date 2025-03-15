<x-knowledgeBase.kb-layout>
 <div class="h-full py-12">
        <div class="h-full p-3 mx-auto w-7/8 sm:px-6 lg:px-8">
            <div class="flex justify-center h-full overflow-hidden sm:rounded-lg">
                <div class="pt-10  w-[80%] ">
                    <form method="POST" action="{{ route('knowledeBase.store')}}">
                        @csrf
                        <div class="grid grid-cols-12 kbgrid">
                            <div class="col-span-12 grid-header">Topic</div>
                            <div class="col-span-12"><input type="text" name="topic"></div>

                            <div class="col-span-12 grid-header">Description</div>
                            <div class="col-span-12"><input type="text" name="description"></div>

                            <div class="col-span-12 grid-header">Text</div>
                            <div class="col-span-12">
                                <textarea name="text" rows="20"></textarea>
                            <div>

                            <div class="col-span-12">
                                <button type="submit" class="cursor-pointer btn" >Add</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>

            </div>

        </div>
 </div>

</x-knowledgeBase.kb-layout>
