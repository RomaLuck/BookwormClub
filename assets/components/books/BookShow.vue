<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {onMounted, reactive, ref} from "vue";
import BookService from "../../services/book.service";
import ReviewService from "../../services/review.service";
import {useRoute} from "vue-router";
import {useUserStore} from "../../store/userStore";

const book = reactive({title: '', author: '', description: ''});
const bookReviews = reactive([]);
const review = reactive({body: '', rating: 0});
const route = useRoute();
const bookId = route.params.id;
const userStore = useUserStore();

const addReview = async () => {
  if (!review.body) {
    return;
  }
  const newReview = {
    body: review.body,
    author: userStore.getEmail || 'Anonymous',
    rating: review.rating,
    book: `/api/books/${bookId}`,
  };
  await ReviewService.create(newReview);
  bookReviews.push(newReview);
  review.body = '';
  review.rating = 0;
}

const rate = (star) => {
  review.rating = star;
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

        <p v-html="book.description" class="border rounded p-2"></p>
        <hr>
        <div class="rating d-flex justify-content-end">
          <span v-for="star in 5" :key="star" :class="{'active': star <= review.rating}">
            <i class="bi bi-star" @click="rate(star)"></i>
          </span>
        </div>
        <textarea class="form-control" name="review" id="review" cols="30" rows="3" placeholder="Add review"
                  v-model="review.body"></textarea>
        <button class="btn btn-primary" @click="addReview">Send</button>
      </div>
      <div class="col-md-3">
        <div class="card" style="width: 18rem;" v-for="bookReview in bookReviews" :key="bookReview.id">
          <div class="card-body">
            <h5 class="card-title">{{ bookReview.author }}</h5>
            <p class="card-text">{{ bookReview.body }}</p>
            <div class="rating">
              <span v-for="star in 5" :key="star" :class="{'active': star <= bookReview.rating || 0}">
                <i class="bi bi-star small-star"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout-div>
</template>

<style scoped>
.rating span {
  cursor: pointer;
  font-size: 2rem;
  color: #ccc;
}

.rating span.active {
  color: gold;
}

.small-star {
  font-size: 1rem;
}
</style>