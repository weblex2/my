@extends('layouts.cv')
@php
    dd($files);
@endphp
<div class="container mx-auto flex-auto pt-20 " >
    <div>Show Source Code </div>
        <div class="showCodeFiles">
            @foreach($files as $file)    
                {{$file->dir}}
            @endforeach
            {{-- @foreach($files as $file)
            <div class="p-1 m-0">
                <div>
                
                
                @if ($file['dir']=="dir")
                    <span class="dir">
                        <i class="fa-regular fa-folder text-yellow-500 dir"></i> 
                        {{ $file['name'] }}  {{$file['dir']}}
                    </span>
                @elseif ($file['dir']=="file")
                    <span>
                        <i class="fa-regular fa-file text-yellow-100 file"></i>
                        <a href="{{ base_path()."/".$file['name'] }}">{{ $file['name'] }}</a>  ( {{$file['dir']}} )
                    </span>
                @endif
                
                </div>    
            </div>
            @endforeach --}}
        </div>

        <div class="showCodeContent">abc</div>
    </div>

    <script>
        
    </script>
