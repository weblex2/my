@extends('layouts.ntfy')
@section('content')
       <div class="py-12  h-screen bg-white">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="sm:rounded-lg h-full">
                <div class="ml-5">
                <p>Idea: <a href="https://docs.ntfy.sh/" target="_blank">https://docs.ntfy.sh/</a></p>
                <p class="mb-5"><img src={{asset("img/ntfy_header.jpeg")}} class="h-[40%]"></p>
                <p>   
                    Install the NTFY App on your phone.<br>
                    Configure it:
                </p>
                </div>
                <div class="flex">
                    
                    <div><img src={{asset("img/ntfy-setup1.jpeg")}} class="w-48 float-left m-5"></div>
                    <div><img src={{asset("img/ntfy-setup2.jpeg")}} class="w-48 float-left m-5"></div>
                    <div class="p-3 m-5">
                    And send the Test message:
                   <form name="createPost" action="{{route("ntfy.sendTestMessage")}}" method="POST"  class="blog">
                        @csrf
                        <div class="grid grid-cols-12 gap-4">
                            <input type="hidden" name="user_id" value="{{ isset(Auth()->user()->id) ? Auth()->user()->id :'' }}">
                            <div class="col-span-12">
                               <input type="text" id="ntfy-topic" name="msg"> 
                            </div>
                            <input type="submit" name="Save" class="btn"> 
                   </form>
                </div>
                </div>    
                
            </div>
        </div>
    </div>
    <script>
        
    </script>

