import axios from "axios";

class ReviewService {
    async showAll() {
        const response = await axios.get("/api/reviews");
        return response.data['member'];
    }

    async create(review) {
        const {body, author, book} = review;

        return await axios.post("/api/reviews", {
            body,
            author,
            book
        }, {
            headers: {
                "Content-Type": "application/ld+json"
            }
        });
    }

    async show(id) {
        const response = await axios.get(`/api/reviews/${id}`);
        return response.data;
    }

    async edit() {
        // Code here
    }

    async update() {
        // Code here
    }

    async delete() {
        // Code here
    }
}

export default new ReviewService();