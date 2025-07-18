<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Leaderboard</h1>

    <div v-if="loading" class="text-center text-gray-500">
      Loading...
    </div>

    <div v-else-if="!board.length" class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-md">
      No one has completed this contest yet.
    </div>

    <div v-else class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">#</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">User</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Score</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Finished At</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr v-for="(row, index) in board" :key="row.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6 font-semibold">{{ index + 1 }}</td>
            <td class="py-3 px-6">{{ row.user.name }}</td>
            <td class="py-3 px-6">{{ row.score }}</td>
            <td class="py-3 px-6">{{ new Date(row.finished_at).toLocaleString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const route = useRoute();
const board = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/v1/leaderboard/${route.params.id}`);
    board.value = data.data;
  } catch (error) {
    console.error("Failed to load leaderboard:", error);
    // Optionally set an error state to show in the UI
  } finally {
    loading.value = false;
  }
});
</script> 