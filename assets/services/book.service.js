import axios from "axios";

class BookService {
    async showAll() {
        const response = await axios.get("/api/books");
        return response.data['member'];
    }

    async create(book) {
        const {title, author, description} = book;

        return await axios.post("/api/books", {
            title,
            author,
            description
        }, {
            headers: {
                "Content-Type": "application/ld+json"
            }
        });
    }

    async show(id) {
        const response = await axios.get(`/api/books/${id}`);
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

export default new BookService();