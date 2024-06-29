<script setup>
import BlueBanner from '@/components/BlueBanner.vue';
</script>

<template>
    <div class="banner-container">
        <BlueBanner v-for="banner in banners" :banner-info="banner" :mode="mode"></BlueBanner>
    </div>
</template>

<script>
export default {
    props: {
        banners: {},
        mode: {}
    },
}
</script>

<style scoped>
.banner-container {
    display: flex;
    flex-wrap: wrap;
    width: fit-content;
    justify-content: center;
}
</style>
