<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">User Management</h1>

    <div v-if="loading" class="text-center text-gray-500">Loading...</div>

    <div v-else-if="error" class="text-red-500 text-center bg-red-100 p-4 rounded-lg shadow-md">{{ error }}</div>

    <div v-else-if="!users.length" class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-md">
      No users found.
    </div>

    <div v-else class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">ID</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Name</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Email</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Role</th>
            <th class="py-3 px-6 text-left font-semibold text-gray-700">Joined</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr v-for="user in users" :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6">{{ user.id }}</td>
            <td class="py-3 px-6">{{ user.name }}</td>
            <td class="py-3 px-6">{{ user.email }}</td>
            <td class="py-3 px-6 uppercase text-xs font-bold">{{ user.role }}</td>
            <td class="py-3 px-6">{{ new Date(user.created_at).toLocaleDateString() }}</td>
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

const users = ref([]);
const loading = ref(true);
const error = ref('');
const authStore = useAuthStore();

const fetchUsers = async () => {
  try {
    const response = await axios.get('/api/v1/admin/users', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
    });
    users.value = response.data.data;
  } catch (err) {
    if (err.response?.status === 403) {
      error.value = 'You are not authorized to view this page.';
    } else {
      error.value = 'An unexpected error occurred while fetching users.';
    }
    console.error('Error fetching users:', err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchUsers();
});
</script> 