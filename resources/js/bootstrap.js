import _ from 'lodash';
import axiosInstance from "./plugins/axios.js";
import 'bootstrap';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import './plugins/echo';

window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = axiosInstance;

// Manually attach X-Socket-ID header to Axios requests
axiosInstance.interceptors.request.use(config => {
    // Retrieve the socket ID from Laravel Echo
    const socketId = window.Echo.socketId();

    // Attach the X-Socket-ID header to the request
    if (socketId) {
        config.headers['X-Socket-ID'] = socketId;
    }

    return config;
}, error => {
    return Promise.reject(error);
});
