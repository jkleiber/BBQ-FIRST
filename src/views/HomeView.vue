<script setup>
import MainPageHeader from '@/components/MainPageHeader.vue';
import SectionFeature from '@/components/SectionFeature.vue';
</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content">
        <div class="section-feature-container">
            <SectionFeature name="Event Rankings"
                description="Interactive event ranking based on BBQ and user defined filters" page="/event-rankings">
            </SectionFeature>
            <SectionFeature name="Team Search" description="Check out how your team stacks up!" page="/team">
            </SectionFeature>
            <SectionFeature name="Event Search" description="Look at the stats for a particular event" page="/event">
            </SectionFeature>
        </div>
    </div>
</template>

<script>

export default {
    computed: {
    },
    methods: {
    },
}
</script>

<style scoped>
.section-feature-container {
    display: flex;
    margin: 0 auto;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}
</style>