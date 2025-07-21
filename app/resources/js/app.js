import { createApp } from 'vue/dist/vue.esm-bundler.js';
import AppLayout from './components/layouts/AppLayout.vue';
import CustomerPanel from './components/customers/CustomerPanel.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    { path: '/customers', component: CustomerPanel },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

const app = createApp({});
app.component('app-layout', AppLayout);
app.use(router);
app.mount('#app');
