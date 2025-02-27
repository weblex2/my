   
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
    <div id="chatroom" class="max-w-md mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-4 bg-gray-200">
            <div class="text-lg font-semibold">Chatroom using Laravel Reverb <a href='/ssc/3' target='_blank' title='Show Source Code'><i class="fa-solid fa-code float-right"></i></a></div>
        </div>
        <div class="p-4">
            <div id="messages" class="h-64 overflow-y-scroll">
                <div class="message">
                    <p><div id="connection-status">Verbindungsstatus: Unbekannt</div> </p>
                </div>
            </div>
            <div class="mt-4">
                <input type="text" id="messageInput" class="w-full p-2 border rounded" placeholder="Type your message here...">
                <button onclick="sendMessage('chat')" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition ease-in-out duration-150">Send Message</button>
                <button onclick="sendMessage('system')" class="mt-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700 transition ease-in-out duration-150 float-right">Send System Message</button>
            </div>
        </div>
    </div>
</div>   