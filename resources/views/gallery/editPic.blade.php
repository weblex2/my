@php
    $country_code  = $pic->Gallery->code;
@endphp
<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight ">
            <a href="/travel-blog">Travel Blog</a> / <a href="{{route('gallery.showGallery',['id' => $pic->Gallery->code])}}">{{$pic->Gallery->code}}</a> / {{$pic->GalleryMappoint->mappoint_name}} /  Edit
        </h2>
    </x-slot>
    <div class="py-12 h-screen overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg p-10 text-orange-500">
                <form id="frmGalleryUpload" action="{{route('gallery.updatePic')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$pic->id}}" />
                    @if ($message = Session::get('success'))
                    <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <div class="mb-5">
                        <img src={{asset($pic->pic)}} class="w-20">
                    </div>
                    <div class="mb-5">
                        <label class="block mb-2  text-sm font-medium text-orange-500" for="file_input">Map Point</label>
                        <div class="p-2 bg-zinc-700 rounded-lg border-1 border-zinc-900 bg-zinc-700 ">
                            <select class="w-full" id="mappoint_id" name="mappoint_id">
                            <option value="">--none--</option>
                            @foreach ($mappoints as $mappoint)
                                <option value="{{ $mappoint->id }}" {{$pic->GalleryMappoint->id==$mappoint->id ? "selected" : "" }}>{{ $mappoint->mappoint_name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text DE</label>
                        <textarea name="contentDE" id="blog-content-de" rows="10" class="rounded-xl border border-gray-300 rounded-lg dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            {{$pic->GalleryTextAll[0]->text}}
                        </textarea>
                        <input type="hidden" name="country_code" value="{{ $country_code }}" />
                    </div>

                    <div class="mt-2">
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text ES</label>
                        <textarea name="contentES" id="blog-content-es" rows="10" class="rounded-xl border border-gray-300 rounded-lg dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            {{$pic->GalleryTextAll[1]->text}}
                        </textarea>
                        <input type="hidden" name="country_code" value="{{ $country_code }}" />
                    </div>

                    <div class="py-5">
                        <input type="submit" class="btn-submit">
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
