<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Gallery / Upload') }}
        </h2>
    </x-slot>
    <div class="py-12 h-screen overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg p-10 text-orange-500">
                <form id="frmGalleryUpload" action="{{route('gallery.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <div>
                        <input type="file" name="file" id="file">
                    </div>
                    <div>
                        <div>Text:</div>
                        <textarea name="content" id="content" rows="10" class="p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">abc {{ date('Y-m-d H:i:s') }}</textarea>
                    </div>
                    <div class="p-5">
                        <input type="submit" class="px-6 py-3 border border-zinc-900 bg-zinc-700 hover:bg-zinc-800 text-orange-500 rounded-xl">Upload
                    </div>
                </form>    
            </div>
        </div>
    </div>
</x-gallery-layout>
