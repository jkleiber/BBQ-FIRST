<script setup>
import { RouterView, useRouter } from 'vue-router';

// import { supabase } from '@/lib/supabase-client';
// import { useAuthStore } from '@/stores/auth-store';

const router = useRouter();
// const authStore = useAuthStore();

// authStore.loadUser();
// 
// supabase.auth.onAuthStateChange((event, session) => {
//     console.log("Auth state changed: " + event)
//     // console.log(session)

//     // If there is no user session, do the SIGNED_OUT action.
//     if (!session) {
//         authStore.clearUser();
//         return;
//     }

//     // Get the user session info.
//     const user = session.user;

//     // Do different actions based on the state change.
//     if (event === 'SIGNED_IN') {
//         authStore.loadUser(user);
//         authStore.loadRedirectRoute();
//     } else if (event === 'SIGNED_OUT') {
//         authStore.clearUser();
//     } else {
//         authStore.loadUser(user);
//     }
// });

// authStore.$onAction(({ name, store, after }) => {
//     if (name === 'loadRedirectRoute') {
//         after(async () => {
//             const redirectRoute = store.redirectRoute;

//             if (redirectRoute) {
//                 await router.isReady();
//                 await router.replace(redirectRoute);
//                 authStore.clearRedirectRoute();
//             }
//         });
//     }
// });
</script>

<template>
    <RouterView />
</template>

<style scoped>
header {
    line-height: 1.5;
}

.logo {
    display: block;
    margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
    header {
        display: flex;
        place-items: center;
        padding-right: calc(var(--section-gap) / 2);
    }

    .logo {
        margin: 0 2rem 0 0;
    }

    header .wrapper {
        display: flex;
        place-items: flex-start;
        flex-wrap: wrap;
    }
}
</style>
