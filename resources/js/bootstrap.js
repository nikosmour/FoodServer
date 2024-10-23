import _ from 'lodash';
import AxiosInstance from "./plugins/axios.js";
import 'bootstrap';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import {EchoInstance, PusherInstance} from './plugins/echo';

window._ = _;
window.Pusher = PusherInstance;

window.Echo = EchoInstance

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = AxiosInstance;

// Manually attach X-Socket-ID header to Axios requests
AxiosInstance.interceptors.request.use(config => {
    // Retrieve the socket ID from Laravel Echo
    const socketId = EchoInstance.socketId();

    // Attach the X-Socket-ID header to the request
    if (socketId) {
        config.headers['X-Socket-ID'] = socketId;
    }

    return config;
}, error => {
    return Promise.reject(error);
});
