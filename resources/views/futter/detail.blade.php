@extends('layouts.futter')
@section('header')
    <h2 class="mt-5 text-xl font-semibold leading-tight text-center text-gray-100">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
@stop
@section('content')
<div class="container flex-auto pt-20 mx-auto">
            @if ($errors->any())
                <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <svg class="inline w-4 h-4 shrink-0 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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
                        <svg class="inline w-4 h-4 shrink-0 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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
                <div class="col-span-2 m-1">Name</div>
                <div class="col-span-10 m-1"><input type="text" name="name" value="{{$futter->name}}"></div>

                <div class="col-span-2 m-1">Zutaten</div>
                <div class="col-span-10 m-1"><textarea rows=10 name="ingredients">{!!implode("\n",$futter->ingredients)!!}</textarea></div>

                <div class="col-span-2 m-1">Zubereitung</div>
                <div class="col-span-10 m-1">
                    <div><textarea  rows=10 name="how_to_make">{{$futter->how_to_make}}</textarea></div>
                </div>

                <div class="items-center col-span-12">
                    <button type="submit" class="float-left btn btn-primary">Save</button>
                    <a href="/futter" type="button" class="float-right btn btn-dark">Back</a>
                </div>
            </div>
            <input type="hidden" name="id" value="{{$futter->id}}">
            </form>
</div>
@stop
