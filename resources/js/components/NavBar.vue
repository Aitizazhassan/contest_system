<template>
  <header class="bg-gray-800 text-white">
    <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
      <!-- Left side: Menu Links -->
      <div class="hidden md:flex items-center space-x-4">
        <router-link :to="{ name: 'contests' }" class="hover:text-gray-300">Contests</router-link>
        <router-link v-if="authStore.isAuth" :to="{ name: 'history' }" class="hover:text-gray-300">History</router-link>
        <router-link v-if="authStore.isAuth" :to="{ name: 'prizes' }" class="hover:text-gray-300">My Prizes</router-link>
        <router-link v-if="authStore.isAdmin" :to="{ name: 'admin' }" class="hover:text-gray-300">Admin</router-link>
        <router-link v-if="authStore.isAdmin" :to="{ name: 'users' }" class="hover:text-gray-300">Users</router-link>
      </div>

      <!-- Right side: User Info & Actions -->
      <div class="hidden md:flex items-center space-x-4">
        <div v-if="authStore.isAuth" class="text-right">
          <p class="font-semibold">{{ authStore.user.name }}</p>
          <p class="text-xs text-gray-400 uppercase">{{ authStore.user.role }}</p>
        </div>
        <button v-if="authStore.isAuth" @click="handleLogout" title="Logout" class="p-2 rounded-full hover:bg-gray-700 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
        <div v-else class="space-x-4">
            <router-link :to="{ name: 'login' }" class="hover:text-gray-300">Login</router-link>
            <router-link :to="{ name: 'register' }" class="bg-blue-600 px-3 py-1 rounded-md hover:bg-blue-700">Register</router-link>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button @click="mobileMenuOpen = !mobileMenuOpen">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </nav>

    <!-- Mobile Menu -->
    <div v-if="mobileMenuOpen" class="md:hidden px-4 pt-2 pb-4 space-y-2">
        <router-link :to="{ name: 'contests' }" class="block hover:text-gray-300">Contests</router-link>
        <router-link v-if="authStore.isAuth" :to="{ name: 'history' }" class="block hover:text-gray-300">History</router-link>
        <router-link v-if="authStore.isAuth" :to="{ name: 'prizes' }" class="block hover:text-gray-300">My Prizes</router-link>
        <router-link v-if="authStore.isAdmin" :to="{ name: 'admin' }" class="block hover:text-gray-300">Admin</router-link>
        <router-link v-if="authStore.isAdmin" :to="{ name: 'users' }" class="block hover:text-gray-300">Users</router-link>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const mobileMenuOpen = ref(false);

async function handleLogout() {
    await authStore.logout();
    router.push('/login');
}
</script> 