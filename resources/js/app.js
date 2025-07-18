import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import { pinia } from './stores'; // Import from the new file
import './bootstrap';

const app = createApp(App);

app.use(pinia);
app.use(router);
app.mount('#app');
