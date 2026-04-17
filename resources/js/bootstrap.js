import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'hjovy9akjespnitckaxc',
    cluster: 'mt1',
    forceTLS: true
});