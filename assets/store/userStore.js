import {defineStore} from 'pinia';
import axios from "axios";

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null
    }),

    actions: {
        async login(email, password) {
            const response = await axios.post('/api/login_check', {
                'username': email,
                'password': password
            });

            if (response.status !== 200) {
                throw new Error('Invalid credentials');
            }

            const token = response.data.token;
            if (!token) {
                throw new Error('Invalid token');
            }

            await this.fetchUser();
        },

        async register(email, username, password) {
            const response = await axios.post('/api/register', {
                email,
                username,
                password
            });

            if (response.status !== 201) {
                throw new Error('Invalid credentials');
            }

            await this.fetchUser();
        },

        async fetchUser() {
            try {
                const response = await axios.get('/api/user/');
                if (response.status === 200) {
                    this.user = await response.data;
                }
            } catch (e) {
                this.user = null;
            }
        },

        async logout() {
            await axios.get('/logout')
            this.user = null;
        }

    },

    getters: {
        isAuthenticated: (state) => !!state.user,
        isAdmin: (state) => state.user && state.user.roles.includes('ROLE_ADMIN')
    }
});