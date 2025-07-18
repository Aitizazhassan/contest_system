<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Available Contests</h1>

    <div v-if="loading" class="text-center text-gray-500">Loading...</div>

    <div v-else-if="!contests.length" class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-md">
      No contests are available at the moment.
    </div>

    <div v-else class="bg-white shadow-md rounded-lg p-4">
      <ul class="space-y-3">
        <li v-for="c in contests" :key="c.id" class="flex items-center justify-between p-3 rounded-md hover:bg-gray-50">
          <div class="flex items-center space-x-4">
            <router-link :to="`/contest/${c.id}`" class="text-blue-600 font-semibold hover:underline">
              {{ c.name }}
            </router-link>
            <span :class="c.access_level === 'vip' ? 'bg-purple-200 text-purple-800' : 'bg-gray-200 text-gray-800'" class="px-2 py-1 text-xs font-bold rounded-full uppercase">
              {{ c.access_level }}
            </span>
          </div>
          
          <span v-if="canParticipate(c)" class="bg-green-200 text-green-800 text-xs font-bold px-3 py-1 rounded-full">
            Participate
          </span>
          <span v-else class="bg-red-200 text-red-800 text-xs font-bold px-3 py-1 rounded-full">
            Not Able to Participate
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const contests = ref([]);
const loading = ref(true);
const authStore = useAuthStore();

const canParticipate = (contest) => {
  if (!authStore.isAuth) {
    return false; // Guests can't participate
  }
  if (authStore.isAdmin || authStore.user?.role === 'vip') {
    return true; // Admins and VIPs can participate in any
  }
  if (authStore.user?.role === 'normal' && contest.access_level === 'normal') {
    return true; // Normal users can participate in normal contests
  }
  return false;
};

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/contests');
    contests.value = data.data;
  } catch (error) {
    console.error("Failed to load contests:", error);
  } finally {
    loading.value = false;
  }
});
</script> 