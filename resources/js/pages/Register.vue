<template>
  <div class="p-4 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Register</h1>
    <div v-if="error" class="bg-red-100 text-red-800 p-2 mb-2 rounded">{{ error }}</div>
    <form @submit.prevent="submit">
      <div class="mb-2">
        <label>Name</label>
        <input v-model="name" type="text" class="border w-full p-2" />
      </div>
      <div class="mb-2">
        <label>Email</label>
        <input v-model="email" type="email" class="border w-full p-2" />
      </div>
      <div class="mb-2">
        <label>Password</label>
        <input v-model="password" type="password" class="border w-full p-2" />
      </div>
      <div class="mb-2">
        <label>Password Confirmation</label>
        <input v-model="passwordConfirmation" type="password" class="border w-full p-2" />
      </div>
      <div v-if="passwordConfirmationError" class="text-red-600 text-sm mt-1">{{ passwordConfirmationError }}</div>
      <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 cursor-pointer">Register</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const error = ref('');
const passwordConfirmationError = ref('');

async function submit() {
  error.value = '';
  try {
    await authStore.register({ name: name.value, email: email.value, password: password.value });
    router.push('/');
  } catch (e) {
    error.value = e.response?.data?.message || 'Registration failed';
  }
}
</script> 