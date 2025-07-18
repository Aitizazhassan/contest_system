<template>
  <div class="p-4" v-if="error">
    <div class="bg-red-100 text-red-800 p-4 rounded text-center">{{ error }}</div>
  </div>
  <div class="p-4" v-else-if="contest">
    <h1 class="text-2xl font-bold mb-4">{{ contest.name }}</h1>
    <form @submit.prevent="submit">
      <div v-for="q in contest.questions" :key="q.id" class="mb-4">
        <p class="font-medium">{{ q.text }}</p>
        <div v-if="q.type === 'boolean'">
          <label v-for="a in q.answers" :key="a.id" class="block">
            <input type="radio" :name="`q_${q.id}`" :value="a.id" v-model="responses[q.id]" /> {{ a.text }}
          </label>
        </div>
        <div v-else-if="q.type === 'single'">
          <label v-for="a in q.answers" :key="a.id" class="block">
            <input type="radio" :name="`q_${q.id}`" :value="a.id" v-model="responses[q.id]" /> {{ a.text }}
          </label>
        </div>
        <div v-else>
          <label v-for="a in q.answers" :key="a.id" class="block">
            <input type="checkbox" :value="a.id" :name="`q_${q.id}`" v-model="responses[q.id]" /> {{ a.text }}
          </label>
        </div>
      </div>
      <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 cursor-pointer">Submit</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const contest = ref(null);
const responses = ref({});
const error = ref('');

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/v1/contests/${route.params.id}`);
    contest.value = data.data;

    // Initialize responses object based on question type
    if (contest.value && contest.value.questions) {
      contest.value.questions.forEach(q => {
        if (q.type === 'multi') {
          responses.value[q.id] = [];
        } else {
          responses.value[q.id] = null;
        }
      });
    }
  } catch (e) {
    console.log(e.response?.status);
    if (e.response?.status === 403) {
      error.value = "You are not authorized to view this contest.";
    } else {
      error.value = "An unexpected error occurred while loading the contest.";
    }
    console.error(e);
  }
});

async function submit() {
  try {
    await axios.post(`/api/v1/contests/${contest.value.id}/submit`, {
      responses: responses.value,
    });
    router.push(`/leaderboard/${contest.value.id}`);
  } catch (e) {
    if (e.response?.status === 403) {
      error.value = "You are not authorized to submit answers for this contest.";
    } else if (e.response?.status == 401) {
      alert('You must be logged in to submit a contest. Redirecting to login...');
      // The global interceptor will handle the redirection.
    } else {
      error.value = "An unexpected error occurred while submitting your answers.";
      console.error('An unexpected error occurred:', e);
    }
  }
}
</script> 