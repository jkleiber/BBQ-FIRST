
import { createRouter, createWebHistory } from 'vue-router';

import HomeView from '@/views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import LogoutView from '@/views/LogoutView.vue';
import DashboardView from '@/views/DashboardView.vue';

// import { authGuard } from '@/guards/auth-guard';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: HomeView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: LoginView,
        meta: {
            requireAuth: false,
            skipIfAuth: true
        }
    },
    {
        path: '/logout',
        name: 'Logout',
        component: LogoutView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: DashboardView,
        meta: {
            requireAuth: true,
            skipIfAuth: false
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// router.beforeEach(authGuard);

export default router;
