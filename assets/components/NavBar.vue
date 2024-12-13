<script setup>
import {computed} from "vue";
import {useUserStore} from "../store/userStore";
import LogoutButton from "./auth/LogoutButton.vue";

const userStore = useUserStore();

const isAuth = computed(() => {
  return userStore.isAuthenticated;
});

const isAdmin = computed(() => {
  return userStore.isAdmin;
});

const toAdminPanel = computed(() => {
  document.location.href = '/admin';
});

</script>

<template>
  <nav class="navbar navbar-expand-lg">
    <div class="container-md">
      <router-link class="navbar-brand" to="/">
        bookworm-club.com
      </router-link>

      <div class="d-flex">
        <div v-if="isAuth">
          <a v-if="isAdmin" class="btn btn-primary me-2" @click="toAdminPanel">Admin panel</a>
          <router-link to="/books" class="btn btn-primary me-2">Books</router-link>
          <LogoutButton/>
        </div>
        <div v-else>
          <router-link to="/login" class="btn btn-outline-primary me-2">Login</router-link>
          <router-link to="/register" class="btn btn-outline-success me-2">Register</router-link>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>