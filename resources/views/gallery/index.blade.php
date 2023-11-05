<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Gallery') }}
        </h2>
    </x-slot>
    <div class="py-12 h-screen overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg">
                <div class="p-3 m-5">
                    <div class="grid grid-cols-4 gap-5">
                        @foreach ($galleries as $gall)
                            <a href = "gallery/show/{{$gall->id}}">
                            <div class="gallery-item bg-[url('/storage/gallery/test/1.JPG')]"><span>{{$gall->name}}</span></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-gallery-layout>
