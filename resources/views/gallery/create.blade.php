<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            {{ __('Gallery / Create') }}
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
                    @elseif (($message = Session::get('error')))
                    <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
                    <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    
                    <div>
                        <div class="text-xl font-extrabold py-5 pl-1">Gallery Name</div>
                        <input type="text" name="name" class="p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                        <div class="text-xl font-extrabold py-5 pl-1">Country Map Name</div>
                        <input type="text" name="country_map_name" class="p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl">
                        <div class="text-xl font-extrabold py-5 pl-1">Country</div>
                        <select name="code" class="mt-2 p-5 w-full bg-zinc-900 border border-zinc-900 rounded-xl focus:outline-none focus:ring-0 focus:border-zinc-900">
                            <option value="">-- select --</option>
                            <option value="BR">Brazil</option>
                            <option value="CO">Colombia</option>
                            <option value="VN">Vietnam</option>
                            <option value="DE">Deutschland</option>
                        </select>    
                    </div>
                    <div class="py-5">
                        <input type="submit" class="cursor-pointer px-6 py-3 border border-zinc-900 bg-zinc-700 hover:bg-zinc-800 text-orange-500 rounded-xl">
                    </div>
                </form>    
            </div>
        </div>
    </div>
</x-gallery-layout>
