import axios from "axios";

class SecurityService {

    async getCsrfToken() {
        const csrfResponse = await axios.get('/api/csrf-token');
        return csrfResponse.data.csrfToken;
    }
}

export default new SecurityService;