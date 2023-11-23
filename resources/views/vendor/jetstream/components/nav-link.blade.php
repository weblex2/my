@props(['active'])

@php
    if (Route::is('gallery*')){
        $text = "bg-green-500";
        $textHover = "border-orange-500";
    }
    else{
        $text = "text-gray-900";
        $textHover = "hover:text-gray-700";
    } 
@endphp

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 {{$text}} focus:outline-none focus:border-indigo-700 transition'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 {{$text}} {{$textHover}} hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
