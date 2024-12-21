<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold  leading-tight ">
            <a href="/travel-blog">Travel Blog</a> / 
            <a href="{{route('gallery.showGallery',['id' => $country_code, 'mappoint_id' => $map_point_id])}}">{{$country_code}}</a> / Upload
        </h2>
    </x-slot>
    <div class="py-12 h-full overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg p-10 text-orange-500">
                <form id="frmGalleryPicUpload" action="{{route('gallery.storepic')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Upload file</label>
                        <input class="p-5 block w-full text-sm text-gray-100 border rounded-lg cursor-pointer border border-zinc-900 bg-zinc-700 " type="file" name="file" id="file">                    
                   </div>
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="mappoint_id">Map Point</label>
                        <div class="p-2 bg-zinc-800 rounded-lg border border-zinc-900">
                            <select class="w-full border-0" id="mappoint_id" name="mappoint_id">
                                <option value="">--none--</option>
                                @foreach ($mappoints as $mappoint)
                                    <option value="{{ $mappoint->id }}" {{$map_point_id==$mappoint->id ? "selected" : "" }}>{{ $mappoint->mappoint_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-orange-500" for="file_input">Text DE</label>
                        <textarea name="content" id="blog-content-de" rows="10" class="rounded-xl border border-gray-300 rounded-lg focus:outline-none p-10 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                           
                        </textarea>
                        <input type="hidden" name="country_code" value="{{ $country_code }}" />
                    </div>

                    <div class="py-5">
                        <button id="submit" type="button" class="btn-submit">Submit</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>

    <div id="save-modal" class="hidden absolute top-0 left-0 w-screen h-screen bg-black bg-opacity-50 flex items-center justify-center">
        <img src="{{asset('img/loading2.webp')}}" class="w-20 h-20">
    </div>

    <script>
        $(document).ready(function(){
            $('#submit').click(function(e){
                //e.preventDefault();
                //$('#busy').css('visibility', 'visible');
                $('#frmGalleryPicUpload').submit();
            }); 
        });

        ClassicEditor
        .create( document.querySelector( '#blog-content-de' ) )
        .catch( error => {
        console.error( error );
        } );
    </script>
</x-gallery-layout>
