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
const connectionStatusDiv = document.getElementById('connection-status');


//console.log(import.meta.env.VITE_PUSHER_APP_CLUSTER);
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: true,  // Um sicherzustellen, dass du wss:// verwendest
    disableStats: true,
    enabledTransports: ['ws', 'wss'], // Beide Protokolle erlauben, je nach Verbindung
    encrypted: true, // Verschlüsselung aktivieren
});

// Event-Listener für Verbindungsstatus

window.Echo.connector.pusher.connection.bind('connecting', () => {
    console.log('Versuche, eine Verbindung zu Reverb herzustellen...');
    connectionStatusDiv.innerHTML = '<i class="fa-solid fa-plug text-yellow-500"></i> Connection: connection establishment...';
});

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Verbindung zu Reverb erfolgreich hergestellt!');
    connectionStatusDiv.innerHTML = '<i class="fa-solid fa-plug-circle-check text-green-500"></i> Connection: connected';
});

window.Echo.connector.pusher.connection.bind('connection_failed', () => {
    console.error('Verbindung zu Reverb fehlgeschlagen!');
    connectionStatusDiv.innerHTML = '<i class="fa-solid fa-plug-circle-xmark text-red-500"></i> Connection: failed';
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.warn('Verbindung zu Reverb getrennt!');
    connectionStatusDiv.innerHTML = 'Connection: Disconnected';
});

window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log('Connection changed:', states);
    connectionStatusDiv.textContent = states;

});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('Fehler bei der Verbindung:', error);
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
