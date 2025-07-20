// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import CustomerPanel from './components/CustomerPanel.vue';

const routes = [
    {
        path: '/customers',
        name: 'customers',
        component: CustomerPanel
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
