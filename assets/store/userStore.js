import {defineStore} from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        token: ''
    }),
    actions: {
        setToken(newToken) {
            this.token = newToken;
        },
        setEmail(newEmail) {
            this.email = newEmail;
        },
        clearToken() {
            this.token = '';
        }
    },
    getters: {
        isAuthenticated: (state) => !!state.token,
        getToken: (state) => state.token,
        getEmail: (state) => state.email
    }
});