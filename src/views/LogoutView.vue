<script setup>
// import { supabase } from '@/lib/supabase-client'
import MainPageHeader from '@/components/MainPageHeader.vue';
</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content" id="logout-message">
    </div>
</template>

<script>
export default {
    mounted() {
        this.logOutUser();
    },
    methods: {
        async logOutUser() {
            // const { error } = await supabase.auth.signOut();

            var msgDiv = document.getElementById('logout-message');
            msgDiv.innerHTML = "Logged out!"
        }
    }
}
</script>