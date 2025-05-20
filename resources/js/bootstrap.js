import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * We're using a direct Pusher connection in each component
 * instead of a global connection to ensure reliability
 */


;

// Set axios defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;


window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'a3922529d7935ab4ff01',
    cluster: 'eu',
});

// Log when the bootstrap file is loaded
console.log('🔄 Bootstrap file loaded successfully');

