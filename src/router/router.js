
import { createRouter, createWebHistory } from 'vue-router';

import HomeView from '@/views/HomeView.vue';
import TeamView from '@/views/TeamView.vue';
import EventView from '@/views/EventView.vue';
import EventRankingsView from '@/views/EventRankingsView.vue';
import PageNotFound from '@/views/PageNotFound.vue'

// Admin pages
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
        path: '/team',
        name: 'Team Search',
        component: TeamView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/team/:team_number',
        name: 'Team',
        component: TeamView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/event',
        name: 'Event Search',
        component: EventView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/event/:event_code',
        name: 'Event',
        component: EventView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/event-rankings',
        name: 'Event Rankings',
        component: EventRankingsView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
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
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: PageNotFound
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// router.beforeEach(authGuard);

export default router;
