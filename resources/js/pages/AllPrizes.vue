<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">All Awarded Prizes</h1>

    <div v-if="loading" class="text-center text-gray-500">Loading...</div>
    <div v-else-if="error" class="text-red-500 text-center bg-red-100 p-4 rounded-lg shadow-md">{{ error }}</div>
    <div v-else-if="!prizes.length" class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-md">
      No prizes have been awarded yet.
    </div>

    <div v-else class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Prize</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Description</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Winner</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Contest</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr v-for="prize in prizes" :key="prize.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6 font-semibold">{{ prize.name }}</td>
            <td class="py-3 px-6">{{ prize.description }}</td>
            <td class="py-3 px-6">{{ prize.user.name }}</td>
            <td class="py-3 px-6">
              <router-link :to="{ name: 'contest.play', params: { id: prize.contest.id } }" class="text-blue-600 hover:underline">
                {{ prize.contest.name }}
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const prizes = ref([]);
const loading = ref(true);
const error = ref('');
const authStore = useAuthStore();

const fetchAllPrizes = async () => {
  try {
    const response = await axios.get('/api/v1/admin/prizes', {
      headers: { Authorization: `Bearer ${authStore.token}` },
    });
    prizes.value = response.data.data;
  } catch (err) {
    if (err.response?.status === 403) {
      error.value = "You are not authorized to view this page.";
    } else {
      error.value = "An unexpected error occurred.";
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchAllPrizes();
});
</script> 