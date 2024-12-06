<script setup>

import {ref, watch} from "vue";

const props = defineProps({
  alert: Object,
})

const visible = ref(true);

watch(() => props.alert, (newAlert) => {
  if (newAlert) {
    visible.value = true;
    setTimeout(() => {
      visible.value = false;
    }, 2000);
  }
});

</script>

<template>
  <ul v-if="alert && visible">
    <li class="alert alert-danger small" v-for="error in alert.violations" :key="error">{{ error.title }}</li>
  </ul>
</template>

<style scoped>

</style>