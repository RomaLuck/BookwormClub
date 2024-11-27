<script setup>
import LayoutDiv from "./LayoutDiv.vue";
import {onMounted, reactive} from "vue";
import BookService from "../services/book.service";

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
    <div class="d-flex">
      <div class="card m-3" style="width: 18rem;" v-for="book in books" :key="book.id">
        <img :src="`uploads/images/${book.image}`" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">{{book.title}}</h5>
          <p class="card-text text-truncate" v-html="book.description"></p>
          <router-link :to="`/books/${book.id}`" class="btn btn-primary">Show</router-link>
        </div>
      </div>
    </div>
  </layout-div>
</template>

<style scoped>

</style>