<x-knowledgeBase.kb-layout>
 <div class="py-12 h-full"> 
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full flex justify-center">
                <div class="pt-10  w-[80%] text-white">
                    

                    <div class="kb-topic">
                        <h1>Topic</h1>
                        <h2>{{$kb->topic}}</h2>
                    </div>
                    <div class="kb-description">
                        <h1>Description</h1>
                        <h2>{{$kb->description}}</h2>
                    </div>
                    <div class="kb-text">
                        <h1>Text</h1>
                        <textarea >{!!$kb->text!!}</textarea>
                        {{-- <div class="w-full h-20 text-white">{!!$kb->text!!}</div> --}}
                    <div>
                    <div>
                        <button type="submit" class="btn float-left mr-3" >Save</button>
                        
                        <form class="float-left" method="POST" action="{{ route('knowledeBase.delete')}}"> 
                            @csrf
                            <input type="hidden" name="id" value="{{ $kb->id }}">
                            <button type="submit" class="btn btn-delete " >Löschen</button>
                        </form>
                        <button type="button" class="btn ml-3" >Zurück</button>
                    </div>
                </div>
                </div>
                   
            </div>
            
        </div>
 </div>    

</x-knowledgeBase.kb-layout>