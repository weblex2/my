<x-gallery-layout>
   
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            {{ __('Gallery / Edit Map Points') }}
        </h2>
    </x-slot>
   
    <div class="py-12 h-screen overflow-auto">

        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 flex justify-center ">
            
            <div class="overflow-hidden w-3/4 sm:rounded-lg p-10 ">
                {{-- <table class="w-full">
                <tr class="text-orange-500">
                    <td>COUNTRY</td>
                    <td>NAME</td>
                    <td>LONGITUDE</td>
                    <td>LATITUDE</td>
                    <td><i class="fa fa-trash" aria-hidden="true"></i></td>
                </tr>
                @foreach ($mappoints as $mp)
                    <tr class="hover:bg-zinc-600">
                        <td><i class="mr-2 fa-solid fa-earth-americas"></i> {{$mp->country_id}}</td>
                        <td><i class="fa-sharp fa-solid fa-city"></i> {{$mp->mappoint_name}}</td>
                        <td>{{$mp->lon}}</td>
                        <td>{{$mp->lat}}</td>
                        <td>
                            <form method="post" action="{{route("gallery.deleteMappoint")}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$mp->id}}" />
                                <button type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </table> --}}
                @php
                    $country_id = "#$";
                @endphp
                <div class="grid grid-cols-7 sortable">
                    <div class="text-orange-500">COUNTRY</div>
                    <div class="text-orange-500">NAME</div>
                    <div class="text-orange-500">LONGITUDE</div>
                    <div>&nbsp;</div>
                    <div class="text-orange-500">LATITUDE</div>
                    <div class="text-orange-500">ORDER</div>
                    <div class="text-orange-500"><i class="fa fa-trash"></i></div>

                    @foreach ($mappoints as $i => $mp)
                        <div class="contents bg-green-500 hover:bg-yellow-500">
                        <div><i class="mr-2 fa-solid fa-earth-americas"></i>&nbsp; {{$mp->country_id}}</div>
                        <div>
                            <a href="/travel-blog/editMappointPics/{{$mp->id}}"><i class="fa-sharp fa-solid fa-city"></i> {{$mp->mappoint_name}}</a>
                        </div>
                        <div>{{$mp->lon}}</div>
                        <div> <a href="javascript_void(0)" onclick="swapLatLong({{$mp->id}})"><i class="fa-solid fa-arrows-left-right"></i></a></div>
                        <div>{{$mp->lat}}</div>
                        <div>{{$mp->ord}}</div>
                        <div>
                            <form method="post" action="{{route("gallery.deleteMappoint")}}">
                                @csrf
                                <input type="hidden" name="id" value="{{$mp->id}}" />
                                <button type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </form>
                        </div>
                     </div>
                @endforeach
                <ul class="sortable">
                    <li>abc</li>
                    <li>def</li>
                </ul>

                </div>
            </div>
        </div>
    </div>
        <script>
        $( function() {
            $( ".sortable" ).sortable();
        } );
        </script>

</x-gallery-layout>
