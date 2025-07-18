<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">My Contest History</h1>

    <div v-if="loading" class="text-center text-gray-500">Loading...</div>

    <div v-else-if="!history.length" class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-md">
      You haven't participated in any contests yet.
    </div>

    <div v-else class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Contest</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Status</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Your Score</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Prize Won</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr v-for="item in history" :key="item.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6">
              <router-link :to="`/contest/${item.contest.id}`" class="text-blue-600 hover:underline">
                {{ item.contest.name }}
              </router-link>
            </td>
            <td class="py-3 px-6">
              <span :class="item.finished_at ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800'" class="px-2 py-1 rounded-full text-xs font-semibold">
                {{ item.finished_at ? 'Finished' : 'In Progress' }}
              </span>
            </td>
            <td class="py-3 px-6">{{ item.score === null ? 'N/A' : item.score }}</td>
            <td class="py-3 px-6">{{ item.contest.prize?.user_id === authStore.user.id ? item.contest.prize.name : 'None' }}</td>
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

const authStore = useAuthStore();
const history = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/history');
    history.value = data.data;
  } catch (error) {
    console.error("Failed to load history:", error);
  } finally {
    loading.value = false;
  }
});
</script> 