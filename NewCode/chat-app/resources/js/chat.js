import Echo from 'laravel-echo';
import axios from 'axios';

// Setup Echo for Reverb
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Get user ID
const userId = document.querySelector('meta[name="user-id"]').content;

// DOM Elements
const messageInput = document.getElementById("message");
const sendBtn = document.getElementById("sendBtn");
const messageContainer = document.getElementById("messages");
const groupSelect = document.getElementById("group");

// Listen for group messages
function listenForMessages(groupId) {
    window.Echo.join(`group.${groupId}`)
        .listen('.message.sent', (data) => {
            displayMessage(data.message.from_id, data.message.message);
        });
}

// Change group selection
groupSelect.addEventListener("change", () => {
    messageContainer.innerHTML = `<p class="text-gray-500">Loading messages...</p>`;
    listenForMessages(groupSelect.value);
    fetchMessages(groupSelect.value);
});

// Send message
sendBtn.addEventListener("click", async () => {
    const message = messageInput.value;
    if (!message) return;

    const groupId = groupSelect.value;

    await axios.post('/chatify/send', {
        group_id: groupId,
        message: message
    });

    messageInput.value = "";
});

// Fetch previous messages
async function fetchMessages(groupId) {
    try {
        const response = await axios.get(`/chatify/messages/${groupId}`);
        messageContainer.innerHTML = "";
        response.data.forEach(msg => {
            displayMessage(msg.from_id, msg.message);
        });
    } catch (error) {
        console.error("Error fetching messages:", error);
    }
}

// Display message in chat
function displayMessage(fromId, message) {
    const msgElement = document.createElement("p");
    msgElement.innerHTML = `<strong>User ${fromId}:</strong> ${message}`;
    messageContainer.appendChild(msgElement);
}

// Load messages on first visit
listenForMessages(groupSelect.value);
fetchMessages(groupSelect.value);