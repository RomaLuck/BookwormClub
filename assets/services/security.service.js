import axios from "axios";
import {useUserStore} from "../store/userStore";


class SecurityService {
    async login(user) {
        const response = await axios.post('/api/login_check', user);
        const token = response.data.token;
        if (!token) { // todo:change to response.status
            throw new Error('Invalid token');
        }
        const userStore = useUserStore();
        userStore.setEmail(user.username)
        userStore.setToken(token);
    }

    logout() {
        const userStore = useUserStore();
        userStore.clearToken();
    }

    isAuthenticated() {
        const userStore = useUserStore();
        return userStore.isAuthenticated;
    }
}

export default new SecurityService();