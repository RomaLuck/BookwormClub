<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {onMounted, onUnmounted, reactive, ref} from "vue";
import BookService from "../../services/book.service";
import ReviewService from "../../services/review.service";
import {useRoute} from "vue-router";
import {useUserStore} from "../../store/userStore";
import Alert from "../Alert.vue";
import DOMPurify from 'dompurify';
import SecurityService from "../../services/security.service";

const book = reactive({title: '', author: '', description: ''});
const bookReviews = ref([]);
const review = reactive({body: '', rating: 0});
const error = ref('');
const route = useRoute();
const bookId = Number(route.params.id);
const userStore = useUserStore();
const isBottomReached = ref(false);
let csrfToken = '';

const addReview = async () => {
  if (!review.body) {
    return;
  }
  const newReview = {
    body: review.body,
    author: userStore.user.username ?? '',
    rating: review.rating,
    book: bookId,
    _csrf_token: csrfToken,
  };
  try {
    await ReviewService.create(newReview);
    bookReviews.value.push(newReview);
    review.body = '';
    review.rating = 0;
  } catch (e) {
    error.value = e.response.data.errors;
  }
}

const rate = (star) => {
  review.rating = star;
}

const handleScroll = () => {
  if (!isBottomReached.value && window.innerHeight + window.scrollY >= document.body.offsetHeight - 10) {
    isBottomReached.value = true;
  }
}

onMounted(async () => {
  csrfToken = await SecurityService.getCsrfToken();

  const response = await BookService.show(bookId);
  const {title, author, description, reviews} = response.data;
  book.title = title;
  book.author = author;
  book.description = DOMPurify.sanitize(description);

  bookReviews.value.push(...reviews);

  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
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
        <Alert :alert="error"/>
        <div class="rating d-flex justify-content-end">
          <span v-for="star in 5" :key="star" :class="{'active': star <= review.rating}">
            <i class="bi bi-star" @click="rate(star)"></i>
          </span>
        </div>
        <textarea class="form-control" name="review" id="review" cols="30" rows="3" placeholder="Add review"
                  v-model="review.body"></textarea>
        <button class="btn btn-primary my-2" @click="addReview">Send</button>
      </div>
      <div :class="{'col-md-3': !isBottomReached, 'col-md-12': isBottomReached}">
        <div class="card mb-2" v-for="bookReview in bookReviews" :key="bookReview.id">
          <div class="card-body">
            <h5 class="card-title">{{ bookReview.author || 'Anonymous' }}</h5>
            <p class="card-text">{{ bookReview.body }}</p>
            <div class="d-flex justify-content-between align-items-center">
              <div class="rating">
                <span v-for="star in 5" :key="star" :class="{'active': star <= bookReview.rating}">
                  <i class="bi bi-star small-star"></i>
                </span>
              </div>
              <div class="small">{{
                  bookReview.publicationDate ? new Date(bookReview.publicationDate).toLocaleDateString() : ''
                }}
              </div>
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