<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            {{ __('Gallery / Create Map Point') }}
        </h2>
    </x-slot>
    <div class="py-12 h-screen overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg p-10 text-orange-500">
                <form id="frmGalleryUpload" action="{{route('gallery.storeMappoint')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                        <strong>{{ $message }}</strong>
                    </div>
                    @elseif (($message = Session::get('error')))
                    <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
                    <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    
                    <div>
                        <div class="text-xl font-extrabold py-5 pl-1">Point Name</div>
                        <input type="text" name="mappoint_name" class="p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                        <div class="text-xl font-extrabold py-5 pl-1">Latitude</div>
                        <input type="text" name="lat" class="p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                        <div class="text-xl font-extrabold py-5 pl-1">Longitude</div>
                        <input type="text" name="lon" class="p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                        <div class="text-xl font-extrabold py-5 pl-1">Country</div>
                        <select name="country_id" class="mt-2 p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                            <option value="">-- select --</option>
                            @foreach($gallery as $gal)
                                <option value="{{$gal->code}}">{{$gal->name}}</option>
                            @endforeach
                        </select>    
                    </div>
                    <div class="py-5">
                        <input type="submit" class="cursor-pointer px-6 py-3 border border-zinc-900 bg-zinc-700 hover:bg-zinc-800 text-orange-500 rounded-xl">
                    </div>
                </form>    
            </div>
        </div>
    </div>

    <script>
        var nh = $('#nav').outerHeight();
        var hh = $('#galheader').outerHeight();
        var total = window.screen.availHeight;
        h = total - nh-hh-120;
        console.log("total:" + total);
        console.log("NAV Height: "+ nh );
        console.log("HEADE Height: "+ hh );
        console.log("height:" + h);
        $('#main').css('height', h);
        $('#main').css('padding', '50px');
        //$('#main').css('border', '1px solid red');
        $('#main').css('bgcolor', 'red');
    </script>
</x-gallery-layout>
