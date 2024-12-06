<script setup>
import {computed} from "vue";
import {useUserStore} from "../store/userStore";
import LogoutButton from "./auth/LogoutButton.vue";

const isAuth = computed(() => {
  const userStore = useUserStore();
  return userStore.isAuthenticated;
});

</script>

<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/">
        <i class="bi bi-houses">Home</i>
      </router-link>

      <div class="d-flex">
        <router-link v-if="isAuth" to="/books" class="btn btn-primary me-2">Books</router-link>
        <LogoutButton v-if="isAuth"/>
        <router-link v-if="!isAuth" to="/login" class="btn btn-outline-primary me-2">Login</router-link>
        <router-link v-if="!isAuth" to="/register" class="btn btn-outline-success me-2">Register</router-link>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>