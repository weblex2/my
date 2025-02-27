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

let userId = getUserIdFromURL();

//console.log(import.meta.env.VITE_PUSHER_APP_CLUSTER);
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: "localhost",
    wsPort: 9002,
    wssPort: 9002, 
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws'],
    encrypted: false,
});

window.Echo.channel('chat')
    .listen('.message.sent', (e) => {
        // Append the new message to the chatroom
        console.log("Message received:", e); 
        const msg = e.message; 
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('p');
        messageElement.innerHTML = e.message;
        messages.prepend(messageElement);
    });


window.Echo.channel('system')
.listen('.message.sent', (e) => {
    // Append the new message to the chatroom
    console.log("Message received:", e); 
    const msg = e.message; 
    const messages = document.getElementById('messages');
    const messageElement = document.createElement('p');
    messageElement.innerHTML = e.message;
    messages.prepend(messageElement);
});

// Function to send a new message
window.sendMessage = function(channel) {
    console.log(channel);
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim(); // Trim whitespace
    let user_id = -1;
    if (channel=='system') {
        user_id = 0;
    }
    else {
        user_id = userId;
    }
    if (message) { // Nur senden, wenn die Nachricht nicht leer ist
        
        axios.post('/chat/send-message', { message: message, user_id: user_id, channel:channel })
            .then(response => {
                console.log(response.data);
                // Clear the input field after sending
                messageInput.value = '';
            })
            .catch(error => console.error(error));

        console.log("Message Sent: " + message);
    }
};

// Event listener for the Enter key
document.getElementById('messageInput').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') { // Überprüfen, ob die Enter-Taste gedrückt wurde
        event.preventDefault(); // Verhindert das Standardverhalten (z. B. Zeilenumbruch)
        window.sendMessage(); // Nachricht senden
    }
});

// Function to get userid from URL
function getUserIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('userid'); // 'userid' aus der URL holen
}