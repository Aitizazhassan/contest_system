<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Admin Panel</h1>

    <div class="mb-4">
        <router-link :to="{name: 'admin.prizes'}" class="text-blue-600 hover:underline">View All Awarded Prizes</router-link>
    </div>

    <section class="mb-8 border p-4 rounded">
      <h2 class="text-xl font-semibold mb-2">Create User</h2>
      <div v-if="userMsg" class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ userMsg }}</div>
      <div v-if="userErr" class="bg-red-100 text-red-800 p-2 mb-2 rounded">{{ userErr }}</div>
      <form @submit.prevent="createUser">
        <div class="mb-2">
          <label>Name</label>
          <input v-model="uName" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Email</label>
          <input v-model="uEmail" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Password</label>
          <input type="password" v-model="uPassword" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Role</label>
          <select v-model="uRole" class="border w-full p-1">
            <option value="vip">VIP</option>
            <option value="normal">Normal</option>
          </select>
        </div>
        <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">Create User</button>
      </form>
    </section>

    <section class="border p-4 rounded mb-8">
      <h2 class="text-xl font-semibold mb-2">Create Contest</h2>
      <div v-if="contestMsg" class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ contestMsg }}</div>
      <div v-if="contestErr" class="bg-red-100 text-red-800 p-2 mb-2 rounded">{{ contestErr }}</div>
      <form @submit.prevent="createContest">
        <div class="mb-2">
          <label>Name</label>
          <input v-model="cName" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Description</label>
          <input v-model="cDesc" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Access Level</label>
          <select v-model="cAccess" class="border w-full p-1">
            <option value="normal">Normal</option>
            <option value="vip">VIP</option>
          </select>
        </div>
        <div class="mb-2 grid grid-cols-2 gap-2">
          <div>
            <label>Start Time</label>
            <input type="datetime-local" v-model="cStart" class="border w-full p-1" />
          </div>
          <div>
            <label>End Time</label>
            <input type="datetime-local" v-model="cEnd" class="border w-full p-1" />
          </div>
        </div>
        <div class="mb-2">
          <label>Prize Name</label>
          <input v-model="pName" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Prize Description</label>
          <input v-model="pDesc" class="border w-full p-1" />
        </div>
        <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 cursor-pointer">Create Contest</button>
      </form>
    </section>

    <section class="border p-4 rounded mt-8">
      <h2 class="text-xl font-semibold mb-2">Add Question to Contest</h2>
      <div v-if="qMsg" class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ qMsg }}</div>
      <div v-if="qErr" class="bg-red-100 text-red-800 p-2 mb-2 rounded">{{ qErr }}</div>
      <form @submit.prevent="createQuestion">
        <div class="mb-2">
          <label>Contest</label>
          <select v-model="qContest" class="border w-full p-1">
            <option
              v-for="contest in contests"
              :key="contest.id"
              :value="contest.id"
            >
              {{ contest.name }}
            </option>
          </select>
        </div>
        <div class="mb-2">
          <label>Question Text</label>
          <input v-model="qText" class="border w-full p-1" />
        </div>
        <div class="mb-2">
          <label>Type</label>
          <select v-model="qType" class="border w-full p-1">
            <option value="single">Single</option>
            <option value="multi">Multi</option>
            <option value="boolean">Boolean</option>
          </select>
        </div>
        <div class="mb-2" v-for="(a, idx) in qAnswers" :key="idx">
          <input v-model="a.text" placeholder="answer text" class="border mr-2 p-1" />
          <label><input type="checkbox" v-model="a.is_correct" /> correct</label>
        </div>
        <div class="items-center space-x-2">
            <button type="button" class="flex-grow bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 cursor-pointer" @click="qAnswers.push({text:'',is_correct:false})">Add Answer</button>
            <button class="flex-grow bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 cursor-pointer">Create Question</button>
        </div>
      </form>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from "../stores/auth";

const store = useAuthStore();
const contests = ref([]);
const newContestName = ref("");
const newContestType = ref("normal");

// Create User refs
const uName = ref('');
const uEmail = ref('');
const uPassword = ref('');
const uRole = ref('vip');
const userMsg = ref('');
const userErr = ref('');

// Create Contest refs
const cName = ref('');
const cDesc = ref('');
const cAccess = ref('normal');
const cStart = ref('');
const cEnd = ref('');
const pName = ref('');
const pDesc = ref('');
const contestMsg = ref('');
const contestErr = ref('');

// Add Question refs
const qContest = ref('');
const qText = ref('');
const qType = ref('single');
const qAnswers = ref([{ text: '', is_correct: false }]);
const qMsg = ref('');
const qErr = ref('');

const fetchContests = async () => {
  try {
    const response = await axios.get("/api/v1/contests", {
      headers: { Authorization: `Bearer ${store.token}` },
    });
    contests.value = response.data.data;
    console.log(contests.value);
    if (contests.value.length > 0) {
      qContest.value = contests.value[0].id;
    }
  } catch (error) {
    console.error("Error fetching contests:", error);
    alert("Failed to fetch contests.");
  }
};

onMounted(() => {
  fetchContests();
});

async function createUser() {
  userMsg.value = userErr.value = '';
  try {
    const res = await axios.post('/api/v1/admin/users', {
      name: uName.value,
      email: uEmail.value,
      password: uPassword.value,
      role: uRole.value,
    });
    userMsg.value = res.data.message;
    uName.value = uEmail.value = uPassword.value = '';
  } catch (e) {
    if (e.response?.status === 401) {
      userErr.value = 'You are not authorized to perform this action. Please log in again.';
    } else {
      userErr.value = e.response?.data?.message || 'An unexpected error occurred.';
    }
  }
}

async function createContest() {
  contestMsg.value = contestErr.value = '';
  try {
    const res = await axios.post('/api/v1/admin/contests', {
      name: cName.value,
      description: cDesc.value,
      access_level: cAccess.value,
      start_time: cStart.value.replace('T', ' '),
      end_time: cEnd.value.replace('T', ' '),
      prize: { name: pName.value, description: pDesc.value },
    });
    contestMsg.value = res.data.message;
    cName.value = cDesc.value = pName.value = pDesc.value = '';
    await fetchContests();
  } catch (e) {
    if (e.response?.status === 401) {
      contestErr.value = 'You are not authorized to perform this action. Please log in again.';
    } else {
      contestErr.value = e.response?.data?.message || 'An unexpected error occurred.';
    }
  }
}

async function createQuestion() {
  qMsg.value = qErr.value = '';
  try {
    const res = await axios.post(`/api/v1/admin/contests/${qContest.value}/questions`, {
      text: qText.value,
      type: qType.value,
      answers: qAnswers.value,
    });
    qMsg.value = res.data.message;
    qContest.value = qText.value = '';
    qType.value = 'single';
    qAnswers.value = [{ text: '', is_correct: false }];
  } catch (e) {
    if (e.response?.status === 401) {
      qErr.value = 'You are not authorized to perform this action. Please log in again.';
    } else {
      qErr.value = e.response?.data?.message || 'An unexpected error occurred.';
    }
  }
}
</script> 