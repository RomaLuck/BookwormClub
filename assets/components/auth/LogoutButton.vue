<script setup>
import {useUserStore} from "../../store/userStore";
import router from "../../router";

const logout = async () => {
  const userStore = useUserStore();
  await userStore.logout();
  await router.push('/');
}
</script>

<template>
  <button class="btn btn-danger" @click="logout">Logout</button>
</template>

<style scoped>

</style>