import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import ContestList from './pages/ContestList.vue';
import ContestPlay from './pages/ContestPlay.vue';
import Leaderboard from './pages/Leaderboard.vue';
import History from './pages/History.vue';
import AdminPanel from './pages/AdminPanel.vue';
import Users from './pages/Users.vue';
import Prizes from './pages/Prizes.vue';
import AllPrizes from './pages/AllPrizes.vue';

const routes = [
  { path: '/login', component: Login, name: 'login' },
  { path: '/register', component: Register, name: 'register' },
  { path: '/', component: ContestList, name: 'contests' },
  { path: '/contest/:id', component: ContestPlay, name: 'contest.play', props: true },
  { path: '/leaderboard/:id', component: Leaderboard, name: 'leaderboard', props: true },
  { path: '/history', component: History, name: 'history' },
  { path: '/admin', component: AdminPanel, name: 'admin' },
  { path: '/users', component: Users, name: 'users' },
  { path: '/prizes', component: Prizes, name: 'prizes' },
  { path: '/admin/prizes', component: AllPrizes, name: 'admin.prizes' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router; 