
// import { useAuthStore } from '@/stores/auth-store';

// export function authGuard(to, from, next) {
//     const authStore = useAuthStore();

//     console.log(from.name + " -> " + to.name);
//     console.log("User logged in: " + authStore.isAuthenticated)

//     // Determine if the user can access the page.
//     const userCanAccessPage = (to.meta.requireAuth && authStore.isAuthenticated) || !to.meta.requireAuth;

//     // If the user is going to a page that should be skipped when they are logged in,
//     // then go to the dashboard.
//     if (to.meta.skipIfAuth && authStore.isAuthenticated) {
//         return next({ name: 'Dashboard' });
//     }

//     // If the user is going to a page that they can access, allow them to do so.
//     if (userCanAccessPage) {
//         return next();
//     }

//     // If the user does not have access to this page, send them to the login page.
//     if (to.name != 'Login') {
//         authStore.saveRedirectRoute(to);
//         return next({ name: 'Login' });
//     }


// }
