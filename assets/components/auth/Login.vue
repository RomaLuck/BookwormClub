<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {ref} from "vue";
import SecurityService from "../../services/security.service";
import router from "../../router";

const email = ref('');
const password = ref('');
const error = ref('');

const login = async () => {
  try {
    await SecurityService.login({
      username: email.value,
      password: password.value
    });

    email.value = '';
    password.value = '';

    await router.push('/');
  } catch (e) {
    error.value = e.response.data.message || e.message;
  }
};

</script>

<template>
  <layout-div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="alert alert-danger" v-if="error">{{ error }}</div>
        <div class="card">
          <div class="card-header">Login</div>
          <div class="card-body">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" aria-describedby="emailHelp" v-model="email">
              <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" v-model="password">
            </div>
            <button class="btn btn-primary" @click="login">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </layout-div>
</template>

<style scoped>

</style>