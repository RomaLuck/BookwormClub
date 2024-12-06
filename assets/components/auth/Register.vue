<script setup>
import LayoutDiv from "../LayoutDiv.vue";
import {ref} from "vue";
import router from "../../router";
import {useUserStore} from "../../store/userStore";
import Alert from "../Alert.vue";

const email = ref('');
const username = ref('');
const password = ref('');
const error = ref('');

const register = async () => {
  const userStore = useUserStore();
  try {
    await userStore.register(email.value, username.value, password.value);

    email.value = '';
    username.value = '';
    password.value = '';

    await router.push('/');
  } catch (e) {
    error.value = e.response.data.errors;
  }
};

</script>

<template>
  <layout-div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <Alert :alert="error"/>
        <div class="card">
          <div class="card-header">Login</div>
          <div class="card-body">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" aria-describedby="emailHelp" v-model="email">
              <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" aria-describedby="usernameHelp" v-model="username">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" v-model="password">
            </div>
            <button class="btn btn-primary" @click="register">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </layout-div>
</template>

<style scoped>

</style>