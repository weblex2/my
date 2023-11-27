<x-gallery-layout>
   
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            {{ __('Gallery / Create') }}
        </h2>
    </x-slot>
   
    <div class="py-12 h-screen overflow-auto">

        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 flex justify-center ">
            
            <div class="overflow-hidden w-3/4 sm:rounded-lg p-10 ">
                <table class="w-full">
                <tr class="text-orange-500">
                    <td><i class="fa fa-trash" aria-hidden="true"></i></td>
                    <td>ID</td>
                    <td>NAME</td>
                    <td>CODE</td>
                    <td>COUNTRY MAP NAME</td>
                    
                </tr>
                @foreach ($galleries as $gall)
                    <tr class="hover:bg-zinc-600">
                    <td>
                        <form method="post" action="{{route("gallery.delete")}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$gall->id}}" />
                            <button type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                    </td>
                    <td>{{$gall->id}}</td>
                    <td>{{$gall->name}}</td>
                    <td>{{$gall->code}}</td>
                    <td>{{$gall->country_map_name}}</td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
</x-gallery-layout>
