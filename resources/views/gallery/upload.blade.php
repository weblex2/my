<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight ">
            <a href="/travel-blog">Gallery</a> / <a href="{{route('gallery.showGallery',['id' => $country_code])}}">{{$country_code}}</a> / Upload
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
                        <input class="p-5 block w-full text-sm text-gray-100 border rounded-lg cursor-pointer border border-zinc-900 bg-zinc-700 " type="file" name="file" id="file">
                    </div>
                    <div class="mb-5">
                        <label class="block mb-2  text-sm font-medium text-orange-500" for="file_input">Map Point</label>
                        <div class="p-2 bg-zinc-700 rounded-lg border-1 border-zinc-900">
                            <select class="w-full" id="mappoint_id" name="mappoint_id">
                            <option value="">--none--</option>
                            @foreach ($mappoints as $mappoint)
                                <option value="{{ $mappoint->id }}">{{ $mappoint->mappoint_name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text DE</label>
                        <textarea name="contentDE" id="blog-content-de" rows="10" class="rounded-xl border border-gray-300 rounded-lg dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            {{ date('Y-m-d H:i:s') }}
                            „Der Schmerz selbst ist Liebe, der Schmerz des Kunden. Aeneas braucht Schmerz. Aeneas Masse Wenn die Berge, deine Heimat und deine Verbündeten und die großen Götter arbeiten werden, wird eine lächerliche Maus geboren. Solange die Katzen noch den Ausgleich haben, die Kids eu, um jeden Preis, sem. Keine Folgen für Masse. Bis der Fuß eben ist, unsere Kunden oder, Bananen oder Denn im Gerechten, im Zen, das, von der Frisur, das giftige Leben, gerecht. Über den weichen Preis des Fußballfußes wurde kein Wort verloren. Ganzzahl Cras dapibus Das Live-Element immer speichern.„
                        </textarea>
                        <input type="hidden" name="country_code" value="{{ $country_code }}" />
                    </div>

                    <div class="mt-2">
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text ES</label>
                        <textarea name="contentES" id="blog-content-es" rows="10" class="rounded-xl border border-gray-300 rounded-lg dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            {{ date('Y-m-d H:i:s') }}
                            Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.
                        </textarea>
                        <input type="hidden" name="country_code" value="{{ $country_code }}" />
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
        .create( document.querySelector( '#blog-content-de' ) )
        .catch( error => {
        console.error( error );
        } );

        ClassicEditor
        .create( document.querySelector( '#blog-content-es' ) )
        .catch( error => {
        console.error( error );
        } );
    </script>
</x-gallery-layout>
