<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {onMounted, ref, watch} from "vue";
import BookService from "../../services/book.service";
import DOMPurify from "dompurify";

const books = ref([]);
const page = ref(1);
const pageNumTotal = ref(1);

const fetchBooks = async () => {
  books.value = [];
  const response = await BookService.showByPage(page.value);
  books.value.push(...response.data);
  page.value = response.currentPage;
  pageNumTotal.value = response.pagesTotal;
};

const truncate = (text, length, clamp = '...') => {
  return text.length > length ? text.slice(0, length) + clamp : text;
}

onMounted(() => {
  fetchBooks();
});

watch(page, () => {
  fetchBooks();
});

</script>

<template>
  <layout-div>
    <h1>Book List</h1>
    <table class="table table-striped">
      <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Description</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="book in books" :key="book.id">
        <td>{{ book.title }}</td>
        <td>{{ book.author }}</td>
        <td v-html="truncate(DOMPurify.sanitize(book.description),100)"></td>
        <td class="d-flex justify-content-end">
          <router-link :to="`/books/${book.id}`" class="btn btn-outline-info">Show</router-link>
        </td>
      </tr>
      </tbody>
    </table>
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item" :class="{disabled:
        page === 1}" @click="page > 1 ? page-- : null">
          <a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li class="page-item" v-for="num in pageNumTotal" :key="num" :class="{active: num === page}"
            @click="page = num">
          <a class="page-link" href="#">{{ num }}</a>
        </li>
        <li class="page-item" :class="{disabled:
        page === pageNumTotal}" @click="page < pageNumTotal ? page++ : null">
          <a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </layout-div>
</template>

<style scoped>

</style>