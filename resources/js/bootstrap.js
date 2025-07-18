import axios from 'axios';
import { useAuthStore } from './stores/auth';
import { pinia } from './stores'; // Import from the new central file

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Pass the pinia instance to the store
const authStore = useAuthStore(pinia);

// Set token from store on initial load
if (authStore.token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;
}

// Add a response interceptor
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // Use the same instance here
            const auth = useAuthStore(pinia);
            auth.logout(true);
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
