import axios from 'axios';

// Create an Axios instance with default settings
export const AxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL, // Use environment variable for API base URL
    timeout: 10000, // Set request timeout limit
    headers: {
        'X-Requested-With': 'XMLHttpRequest', // Laravel uses this to identify Ajax requests
        'Content-Type': 'application/json',
    }
});

// Interceptors for handling requests and responses
/*axiosInstance.interceptors.request.use(
    (config) => {
        // Example: Attach token if available
        const token = localStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    (response) => response,
    (error) => {
        // Handle error globally (e.g., redirect to login on 401)
        if (error.response && error.response.status === 401) {
            // Redirect to login or refresh token
        }
        return Promise.reject(error);
    }
);*/

export default AxiosInstance;
