<template>
  <div class="p-4 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Login</h1>
    <div v-if="error" class="bg-red-100 text-red-800 p-2 mb-2 rounded">{{ error }}</div>
    <form @submit.prevent="submit">
      <div class="mb-2">
        <label>Email</label>
        <input v-model="email" type="email" class="border w-full p-2" :class="{'border-red-500': emailError}" />
        <div v-if="emailError" class="text-red-600 text-sm mt-1">{{ emailError }}</div>
      </div>
      <div class="mb-2">
        <label>Password</label>
        <input v-model="password" type="password" class="border w-full p-2" :class="{'border-red-500': passwordError}" />
        <div v-if="passwordError" class="text-red-600 text-sm mt-1">{{ passwordError }}</div>
      </div>
      <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">Login</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const email = ref('');
const password = ref('');
const error = ref('');
const emailError = ref('');
const passwordError = ref('');

async function submit() {
  error.value = '';
  emailError.value = '';
  passwordError.value = '';
  try {
    await authStore.login({ email: email.value, password: password.value });
    router.push('/');
  } catch (e) {
    if (e.response && e.response.status === 422) {
      const validationErrors = e.response.data.errors;
      if (validationErrors.email) {
        emailError.value = validationErrors.email[0];
      }
      if (validationErrors.password) {
        passwordError.value = validationErrors.password[0];
      }
    } else {
      error.value = e.response?.data?.message || 'Login failed';
    }
  }
}
</script> 