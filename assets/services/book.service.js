import axios from "axios";

class BookService {
    async showByPage(page) {
        const response = await axios.get("/api/books/",{
            params: {
                page: page
            }
        });
        return response.data;
    }

    async search(search) {
        const response = await axios.get("/api/books/", {
            params: {
                search: search
            }
        });
        return response.data;
    }

    async getTopRatedBooks() {
        const response = await axios.get("/api/books/top");
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