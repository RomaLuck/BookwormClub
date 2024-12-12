<script setup>
import {onMounted, reactive} from "vue";
import LayoutDiv from "./LayoutDiv.vue";
import BookService from "../services/book.service";
import DOMPurify from "dompurify";

const books = reactive([]);

const fetchBooks = async () => {
  const response = await BookService.getTopRatedBooks();
  books.push(...response.data);
};

const truncate = (text, length, clamp = '...') => {
  return text.length > length ? text.slice(0, length) + clamp : text;
}

onMounted(() => {
  fetchBooks();
});

</script>

<template>
  <layout-div>
    <div class="row row-cols-4">
      <div class="col card m-2" style="width: 18rem;" v-for="book in books" :key="book.id">
        <img v-if="book.image" :src="`uploads/images/${book.image}`" class="card-img-top" alt="...">
        <div class="card-body position-relative">
          <h5 class="card-title">{{ book.title }}</h5>
          <p class="card-text" v-html="truncate(DOMPurify.sanitize(book.description),100)"></p>
          <router-link :to="`/books/${book.id}`" class="btn btn-primary">Show</router-link>
          <i class="bi bi-heart position-absolute bottom-0 end-0 m-2"></i>
        </div>
      </div>
    </div>
  </layout-div>
</template>

<style scoped>

</style>