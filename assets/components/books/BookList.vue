<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {onMounted, reactive, ref} from "vue";
import BookService from "../../services/book.service";

const books = reactive([]);

const fetchBooks = async () => {
  books.push(...await BookService.showAll());
};

onMounted(() => {
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
        <td class="text-truncate" v-html="book.description"></td>
        <td class="d-flex justify-content-end">
          <router-link :to="`/books/${book.id}`" class="btn btn-outline-info">Show</router-link>
        </td>
      </tr>
      </tbody>
    </table>
  </layout-div>
</template>

<style scoped>

</style>