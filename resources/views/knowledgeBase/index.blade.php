<x-knowledgeBase.kb-layout>
 <div class="h-full py-12">
        <div class="h-full p-3 mx-auto w-7/8 sm:px-6 lg:px-8">
            <div class="flex justify-center h-full overflow-hidden sm:rounded-lg">
                <div class="pt-10  w-[80%]">
                    @foreach ($kbs as $kb)
                    <div class="kb-grid">
                        <div class="topic">
                            <a href="{{route('knowledgeBase.webshow', $kb->id)}}"> {{$kb->topic}}</a>
                        </div>
                        <div class="description">{{$kb->description}}</div>
                        <div class="text">
                            <form method="POST" action="{{ route('knowledeBase.delete', ['id' => $kb->id] )}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $kb->id }}">
                            <button type="submit" class="cursor-pointer" ><i class="float-left fa-solid fa-trash"></i></button>
                            </form>
                        </div>
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

</x-knowledgeBase.kb-layout>