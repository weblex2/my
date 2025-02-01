<x-noppal>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-center text-gray-100 leading-tight">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
</x-slot>
<div class="container mx-auto flex-auto pt-20">
            <form method="POST" action="/futter/update">
            @csrf
            <div class="grid grid-cols-12">
                <div class="col-span-12 m-1">{{$futter->name}}</div>

                <div class="col-span-2 m-1">Zutaten</div>
                <div class="col-span-10 m-1"><textarea name="ingredients">{!!implode("\n",$futter->ingredients)!!}</textarea></div>

                <div class="col-span-2 m-1">Zubereitung</div>
                <div class="col-span-10 m-1">
                    <div>{!! nl2br($futter->how_to_make)!!}</div>
                </div>    

                <div class="col-span-12 items-center">
                    <a href="/futter" type="button" class="btn btn-primary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            <input type="hidden" name="id" value="{{$futter->id}}">
            </form>
</div>


</x-noppal>