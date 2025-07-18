<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">My Prizes</h1>

    <div v-if="loading" class="text-center text-gray-500">Loading...</div>

    <div v-else-if="!prizes.length" class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-md">
      You haven't won any prizes yet.
    </div>

    <div v-else class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Prize</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Description</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Won In Contest</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr v-for="prize in prizes" :key="prize.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6 font-semibold">{{ prize.name }}</td>
            <td class="py-3 px-6">{{ prize.description }}</td>
            <td class="py-3 px-6">
              <router-link :to="`/contest/${prize.contest.id}`" class="text-blue-600 hover:underline">
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

const prizes = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/v1/prizes');
    prizes.value = data.data;
  } catch (error) {
    console.error("Failed to load prizes:", error);
  } finally {
    loading.value = false;
  }
});
</script> 