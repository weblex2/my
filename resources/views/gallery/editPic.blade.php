@php
    $country_code  = $pic->Gallery->code;
    $pic_text = "No text available";
    if (isset($pic->GalleryText[0]->text)){
        $pic_text = $pic->GalleryText[0]->text;
    }

@endphp
<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold leading-tight ">
            <a href="/travel-blog">Travel Blog</a> / 
            <a href="{{route('gallery.showGallery',['id' => $pic->Gallery->code])}}">{{$pic->Gallery->name}}</a> / 
            <a href="{{route('gallery.showGallery',['id' => $pic->Gallery->code, 'mappoint_id' => $pic->GalleryMappoint->id])}}">{{$pic->GalleryMappoint->mappoint_name}}</a> /  Edit
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
                        <img src={{$pic->Thumbnail->filecontent}}>
                    </div>
                    <div class="mb-5">
                        <label class="block mb-2  text-sm font-medium text-orange-500" for="file_input">Map Point</label>
                            <select class="w-full bg-zinc-700" id="mappoint_id" name="mappoint_id">
                            <option value="">--none--</option>
                            @foreach ($mappoints as $mappoint)
                                <option value="{{ $mappoint->id }}" {{$pic->GalleryMappoint->id==$mappoint->id ? "selected" : "" }}>{{ $mappoint->mappoint_name }}</option>
                            @endforeach
                            </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text</label>
                        <textarea name="content" id="blog-content-de" rows="10" class="rounded-xl border border-gray-300 rounded-lg dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            {{$pic_text}}
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
    </script>
</x-gallery-layout>
