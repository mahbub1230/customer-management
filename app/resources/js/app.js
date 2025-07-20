import { createApp } from 'vue/dist/vue.esm-bundler.js';
import AppLayout from './components/AppLayout.vue';
import router from './router';

const app = createApp({});
app.component('app-layout', AppLayout);
app.use(router);
app.mount('#app');

