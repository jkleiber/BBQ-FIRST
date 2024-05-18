<script setup>
import { RouterView } from 'vue-router';
import { viewModeStore } from '@/stores/view-mode-store';

// Keep track of the view mode based on screen width.
const viewMode = viewModeStore();
window.addEventListener('resize', () => {
    viewMode.updateScreenWidth(window.innerWidth);
})

</script>

<template>
    <!-- 
        Using route.fullPath results in the pages updating when 
        the route params change, which is critical for search to 
        work when searching consecutive events/teams.
    -->
    <RouterView :key="$route.fullPath" />
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
