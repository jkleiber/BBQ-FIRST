<script setup>
import '@material/web/textfield/outlined-text-field.js';
import '@material/web/list/list.js';

// Import supabase
// import { supabase } from '@/lib/supabase-client'


import MainPageHeader from '@/components/MainPageHeader.vue';


</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content">
        <div id="googleLoginButton">
        </div>
    </div>
</template>

<script>

export default {
    mounted() {
        var googleButton = document.getElementById('googleLoginButton')

        google.accounts.id.initialize({
            client_id: "insert-client-id",
            callback: this.handleSignInWithGoogle
        });

        google.accounts.id.renderButton(
            googleButton,
            {
                // Options go here, for example:
                type: "standard",
                shape: "pill",
                theme: "filled_blue",
                text: "signin_with",
                size: "large",
                logo_alignment: "left",
            }
        );

        google.accounts.id.prompt();
    },
    computed: {},
    methods: {
        async handleSignInWithGoogle(response) {
            // Sign the user in.
            this.signIn(response, 'google');
        },
        async signIn(response, provider) {
            // try {
            //     const { data, error } = await supabase.auth.signInWithIdToken({
            //         provider: provider,
            //         token: response.credential
            //     });

            //     if (error) {
            //         throw error;
            //     }
            // } catch (error) {
            //     localStorage.removeItem('redirectRoute');
            //     console.error(error);
            //     alert('Something went wrong! Check the console for more details');
            // }
        }
    }
}
</script>
