<script setup>
// import { supabase } from '@/lib/supabase-client'
import MainPageHeader from '@/components/MainPageHeader.vue'

import '@material/web/textfield/outlined-text-field.js';
import '@material/web/list/list.js';

import '@/assets/bbq.css';

// import { useAuthStore } from '@/stores/auth-store';
</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content">
        <h1>Dashboard</h1>
    </div>
</template>

<script>

export default {
    data() {
        return {
            authStore: null
        }
    },
    mounted() {
    },
    computed: {
        username() {
        },
    },
    methods: {
    },
}
</script>

<style scoped></style>