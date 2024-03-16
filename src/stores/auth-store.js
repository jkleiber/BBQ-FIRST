import { defineStore } from 'pinia';
// import { supabase } from '@/lib/supabase-client'


// export const useAuthStore = defineStore('auth', {
//     state() {
//         return {
//             currentUser: null,
//             redirectRoute: null
//         };
//     },
//     getters: {
//         isAuthenticated() {
//             return !!this.currentUser;
//         }
//     },
//     actions: {
//         loadUser(user) {
//             this.currentUser = user;
//         },
//         clearUser() {
//             this.currentUser = null;
//         },
//         saveRedirectRoute(route) {
//             const { name, params, query, hash } = route;

//             console.log("Redirect saved: " + name)

//             localStorage.setItem(
//                 'redirectRoute',
//                 JSON.stringify({
//                     name,
//                     params,
//                     query,
//                     hash
//                 })
//             );
//         },
//         loadRedirectRoute() {
//             const route = JSON.parse(
//                 localStorage.getItem('redirectRoute') || 'null'
//             );

//             this.redirectRoute = route;
//         },
//         clearRedirectRoute() {
//             localStorage.removeItem('redirectRoute');
//             this.redirectRoute = null;
//         }
//     }
// }
// );