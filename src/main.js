import '@/assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia';
import router from './router/router'

import App from '@/App.vue'

// Create and mount the root instance.
const app = createApp(App);

// Register the router and the Pinia store with the app 
// so it knows its active view and state.
app.use(router);
app.use(createPinia());

// Mount the app.
app.mount('#app');
