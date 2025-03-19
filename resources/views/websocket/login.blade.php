
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatroom</title>
    @vite(['resources/css/chat.css', 'resources/js/app.js', 'resources/js/websocket.js'])


</head>
<body>
<div id="app" class="p-6">
    @csrf
    <div id="chatroom" class="max-w-md mx-auto overflow-hidden bg-white rounded-lg shadow-lg">
        <div class="p-4 bg-gray-200">
            <div class="text-lg font-semibold"><img class="reverb-icon" src="img/reverb.png"> Chatroom using Laravel Reverb <a href='/ssc/3' target='_blank' title='Show Source Code'><i class="ssc-icon"></i></a></div>
        </div>
        <div class="p-4">
            <form method="POST" action="{{route("chat.login")}}">
            @csrf
            <div class="mt-4">
                <h1 class="my-3 font-semibold">Login as </h1>
                <select id="user" name="userid" class="w-full p-2 border rounded">
                    <option value="25">Fritz</option>
                    <option value="36">Herbert</option>
                </select>
                <button type="submit" class="px-4 py-2 mt-2 text-white transition duration-150 ease-in-out bg-blue-500 rounded hover:bg-blue-700">Login</button>
            </div>
            </form>
            {{-- <div id="connection-status">
                <i class="text-yellow-500 fa-solid fa-plug-circle-exclamation">
                </i> Connection: connecting...</div>
            </div> --}}
    </div>
</div>
