import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY, // should match your .env key
    cluster: process.env.MIX_PUSHER_APP_CLUSTER, // should match your .env cluster
    forceTLS: true, // use TLS if your site is served over HTTPS
});
