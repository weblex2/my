<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight ">
            <a href="/gallery">Gallery</a> / <a href="/gallery/show/{{$gal_id}}">{{$gal_id}}</a> / Upload
        </h2>
    </x-slot>
    <div class="py-12 h-screen overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg p-10 text-orange-500">
                <form id="frmGalleryUpload" action="{{route('gallery.storepic')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <div class="mb-5">
                        {{-- <input type="file" name="file" id="file"> --}}
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Upload file</label>
                        <input class="p-5 block w-full text-sm text-gray-100 border border-gray-300 rounded-lg cursor-pointer bg-zinc-900 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="file" id="file">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text</label>
                        <textarea name="content" id="blog-content" rows="10" class="dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            {{ date('Y-m-d H:i:s') }}
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                        </textarea>
                        <input type="hidden" name="country_code" value="{{ $gal_id }}" />
                    </div>
                    <div class="py-5">
                        <input type="submit" class="px-6 py-3 border border-zinc-900 bg-zinc-700 hover:bg-zinc-800 text-orange-500 rounded-xl">
                    </div>
                </form>    
            </div>
        </div>
    </div>
    <script>
        ClassicEditor
        .create( document.querySelector( '#blog-content' ) )
        .catch( error => {
        console.error( error );
        } );
    </script>
</x-gallery-layout>
