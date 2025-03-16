import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// ✅ **Listen for Private Messages**
const userId = document.head.querySelector('meta[name="user-id"]')?.content;

if (userId) {
    window.Echo.private(`chatify.${userId}`)
        .listen('.message.sent', (data) => {
            console.log("🔵 Private Message Received:", data);
            // Handle updating UI for private messages
        });
}

// ✅ **Listen for Group Messages**
const currentGroupId = window.currentGroupId || null;  // This should be set dynamically when in a group chat

if (currentGroupId) {
    window.Echo.join(`group.${currentGroupId}`)
        .listen('.message.sent', (data) => {
            console.log("🟢 Group Message Received:", data);
            // Handle updating UI for group messages
        });
}
