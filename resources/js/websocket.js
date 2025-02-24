// Listen for new messages using Laravel Echo
/*
import Echo from 'laravel-echo';
 
import Pusher from 'pusher-js';
window.Pusher = Pusher;
console.log(import.meta.env.VITE_REVERB_PORT);
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

*/

//document.querySelector('meta[name="csrf-token"]').getAttribute('content');

console.log("websocket am start");

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 9002,
    wssPort: 443, 
    forceTLS: true,
    disableStats: true,
    enabledTransports: ['ws','wss'],
    encrypted: true,
    
});

window.Echo.channel('message')
    .listen('.message.sent', (e) => {
        // Append the new message to the chatroom
        console.log("Message received:", e); 
        const msg = JSON.parse(e.message); 
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('p');
        messageElement.innerText = e.message;
        messages.appendChild(messageElement);
    });

// Function to send a new message
window.sendMessage = function() {
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    const messageInput = document.getElementById('messageInput');
    console.log("Message Sent:" + messageInput.value);
    const message = messageInput.value;
    axios.post('/chat/send-message', { message: message })
        .then(response => {
            console.log(response.data);
            // Clear the input field after sending
            messageInput.value = '';
        })
        .catch(error => console.error(error));
 
    console.log('sent message');
};
