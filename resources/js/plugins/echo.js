import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

export const PusherInstance = Pusher;
export const EchoInstance = new Echo({
    broadcaster: 'PusherInstance',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});


