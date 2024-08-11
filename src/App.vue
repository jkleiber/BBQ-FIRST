<script setup>
import { RouterView } from 'vue-router';
import { useViewModeStore } from '@/stores/view-mode-store';
import MainPageHeader from './components/MainPageHeader.vue';
import Footer from './components/Footer.vue';

// Keep track of the view mode based on screen width.
const viewMode = useViewModeStore();
window.addEventListener('resize', () => {
    viewMode.updateScreenWidth(window.innerWidth);
})

</script>

<template>
    <MainPageHeader></MainPageHeader>
    <!-- 
        Using route.fullPath results in the pages updating when 
        the route params change, which is critical for search to 
        work when searching consecutive events/teams.
    -->
    <RouterView :key="$route.fullPath" />

    <Footer></Footer>
</template>

<style scoped></style>
