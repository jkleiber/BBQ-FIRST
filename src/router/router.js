
import { createRouter, createWebHistory } from 'vue-router';

import HomeView from '@/views/HomeView.vue';
import TeamView from '@/views/TeamView.vue';
import EventView from '@/views/EventView.vue';
import EventRankingsView from '@/views/EventRankingsView.vue';
import TeamRankingsView from '@/views/TeamRankingsView.vue';
import SearchView from '@/views/SearchView.vue';
import PageNotFound from '@/views/PageNotFound.vue'

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
        path: '/teams',
        name: 'Team Rankings',
        component: TeamRankingsView,
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
        path: '/events',
        name: 'Event Rankings',
        component: EventRankingsView,
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
        path: '/search/',
        name: 'BBQ Search',
        component: SearchView,
        meta: {
            requireAuth: false,
            skipIfAuth: false
        }
    },
    {
        path: '/search/:query',
        name: 'Search',
        component: SearchView,
        meta: {
            requireAuth: false,
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
