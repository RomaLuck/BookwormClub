import axios from "axios";

class ReviewService {
    async showAll() {
        const response = await axios.get("/api/reviews");
        return response.data['member'];
    }

    async create(review) {
        return await axios.post("/api/reviews", review, {
            headers: {
                "Content-Type": "application/ld+json"
            }
        });
    }

    async show(id) {
        const response = await axios.get(`/api/reviews/${id}`);
        return response.data;
    }

    async update(id, data) {
        const response = await axios.patch(`/api/reviews/${id}`, data, {
            headers: {
                "Content-Type": "application/merge-patch+json"
            }
        });
        return response.data;
    }

    async delete(id) {
        await axios.delete(`/api/reviews/${id}`);
    }
}

export default new ReviewService();