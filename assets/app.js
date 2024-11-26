import './styles/app.css';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

import {createApp} from 'vue';
import App from "./App.vue";
import {createRouter, createWebHistory} from "vue-router";
import BookList from "./components/books/BookList.vue";
import BookShow from "./components/books/BookShow.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {path: '/books', component: BookList},
        {path: '/books/:id', component: () => BookShow},
    ],
})

const app = createApp(App);
app.use(router);
app.provide('bootstrap', bootstrap);
app.mount('#app');
