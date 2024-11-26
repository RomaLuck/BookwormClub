<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {onMounted, reactive, ref} from "vue";
import BookService from "../../services/book.service";
import ReviewService from "../../services/review.service";
import {useRoute} from "vue-router";

const book = reactive({title: '', author: '', description: ''});
const bookReviews = reactive([]);
const review = ref('');
const route = useRoute();
const bookId = route.params.id;

const addReview = async () => {
  await ReviewService.create({
    body: review.value,
    author: 'Anonymous',
    book: `/api/books/${bookId}`,
  });
  review.value = '';
}

onMounted(async () => {
  const {title, author, description, reviews} = await BookService.show(bookId);
  book.title = title;
  book.author = author;
  book.description = description;

  const reviewPromises = reviews.map(r => {
    const reviewId = r.match(/\/api\/reviews\/(\d+)/)[1];
    return ReviewService.show(reviewId);
  });

  const resolvedReviews = await Promise.all(reviewPromises);
  bookReviews.push(...resolvedReviews);
});

</script>

<template>
  <layout-div>
    <h1>{{ book.title }}</h1>
    <div class="row">
      <p>{{ book.author }}</p>
      <div class="col-md-9">

        <textarea class="form-control" name="description" id="description" cols="30" rows="10" disabled>
      {{book.description}}
    </textarea>
        <hr>

      <textarea class="form-control" name="review" id="review" cols="30" rows="3" placeholder="Add review"
                v-model="review"></textarea>
          <button class="btn btn-primary" @click="addReview">Send</button>
      </div>
      <div class="col-md-3">
        <div class="card" style="width: 18rem;" v-for="bookReview in bookReviews" :key="bookReview.id">
          <div class="card-body">
            <h5 class="card-title">{{ bookReview.author }}</h5>
            <p class="card-text">{{ bookReview.body }}</p>
          </div>
        </div>
      </div>
    </div>
  </layout-div>
</template>

<style scoped>

</style>