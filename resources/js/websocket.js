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
console.log(import.meta.env.VITE_REVERB_PORT);
window.Echo.channel('message')
    .listen('.message.sent', (e) => {
        console.log(e);
        // Append the new message to the chatroom
        const messages = document.getElementById('messages');
        const messageElement = document.createElement('p');
        messageElement.innerText = e.message;
        messages.appendChild(messageElement);
    });

// Function to send a new message
window.sendMessage = function() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value;
    axios.post('/chat/send-message/', { message: message })
        .then(response => {
            console.log(response.data);
            // Clear the input field after sending
            messageInput.value = '';
        })
        .catch(error => console.error(error));
 
    console.log('sent message');
};
