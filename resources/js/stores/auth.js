import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null,
    }),

    getters: {
        isAuth: (state) => !!state.token,
        isAdmin: (state) => state.user?.role === 'admin',
    },

    actions: {
        async login(credentials) {
            const response = await axios.post('/api/v1/auth/login', credentials);
            const { data } = response.data; // unwrap the ApiResponse
            
            this.token = data.token;
            this.user = data.user;

            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
        },

        async register(details) {
            const response = await axios.post('/api/v1/auth/register', details);
            const { data } = response.data; // unwrap the ApiResponse

            this.token = data.token;
            this.user = data.user;

            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
        },

        async logout(force = false) {
            if (!force) {
              try {
                  await axios.post('/api/v1/auth/logout');
              } catch (e) {
                  // ignore if it fails, as we're logging out anyway
              }
            }

            this.token = null;
            this.user = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            delete axios.defaults.headers.common['Authorization'];
        },
    },
}); 