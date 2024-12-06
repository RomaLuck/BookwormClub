import axios from "axios";

class BookService {
    async showAll() {
        const response = await axios.get("/api/books/");
        return response.data;
    }

    async create(book) {
        await axios.post("/api/books/", book, {
            headers: {
                "Content-Type": "application/ld+json"
            }
        });
    }

    async show(id) {
        const response = await axios.get(`/api/books/${id}`);
        return response.data;
    }
}

export default new BookService();