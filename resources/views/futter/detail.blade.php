<x-noppal>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-center text-gray-100 leading-tight">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
</x-slot>
<div class="container mx-auto flex-auto pt-20">
            @if ($errors->any())
                <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Danger alert!</span> Change a few things up and try submitting again.
                </div>
                </div>
            @elseif ($message = Session::get('success'))
                <div class="flex items-center p-4 mb-2 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <div class="flex items-center px-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <span class="font-medium">{{$message}}</span>
                        </div>
                    </div>
                </div>    
            @endif
            <form method="POST" action="/futter/update">
            @csrf
            <div class="grid grid-cols-12">
                <div class="col-span-12 m-1">{{$futter->name}}</div>

                <div class="col-span-2 m-1">Zutaten</div>
                <div class="col-span-10 m-1"><textarea rows=10 name="ingredients">{!!implode("\n",$futter->ingredients)!!}</textarea></div>

                <div class="col-span-2 m-1">Zubereitung</div>
                <div class="col-span-10 m-1">
                    <div><textarea  rows=10 name="how_to_make">{{$futter->how_to_make}}</textarea></div>
                </div>    

                <div class="col-span-12 items-center">
                    <a href="/futter" type="button" class="btn btn-primary">Back</a>
                    <button type="submit" class="btn btn-primary float-right">Save</button>
                </div>
            </div>
            <input type="hidden" name="id" value="{{$futter->id}}">
            </form>
</div>


</x-noppal>