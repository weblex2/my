<x-knowledgeBase.kb-layout>
 <div class="h-full py-12">
        <div class="h-full p-3 mx-auto w-7/8 sm:px-6 lg:px-8">
            <div class="flex justify-center h-full overflow-hidden sm:rounded-lg">
                <div class="pt-10  w-[80%]">


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
                        <button type="submit" class="float-left mr-3 btn" >Save</button>

                        <form class="float-left" method="POST" action="{{ route('knowledeBase.delete')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $kb->id }}">
                            <button type="submit" class="btn btn-delete " >Löschen</button>
                        </form>
                        <button type="button" class="ml-3 btn" >Zurück</button>
                    </div>
                </div>
                </div>

            </div>

        </div>
 </div>

</x-knowledgeBase.kb-layout>