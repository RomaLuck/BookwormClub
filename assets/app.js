import './styles/app.css';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';
import { createPinia } from 'pinia';

import {createApp} from 'vue';
import App from "./App.vue";
import router from "./router";

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(pinia);

app.provide('bootstrap', bootstrap);
app.mount('#app');
