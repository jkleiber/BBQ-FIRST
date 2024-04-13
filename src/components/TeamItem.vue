<script setup>
</script>

<template>
    <div class="team-container">
        <div class="team">
            {{ number }} - {{ name }} - {{ country }}
        </div>
        <div class="award-list">
            <div v-for="award in robotAwards">
                {{ award.award_name }} @ {{ award.event_name }} {{ award.event_year }}
            </div>
            <div v-for="award in teamAwards">
                {{ award.award_name }} @ {{ award.event_name }} {{ award.event_year }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['number', 'name', 'country', 'robot-awards', 'team-awards']
}
</script>

<style scoped>
.team-container {
    margin-bottom: 5px;
}

.team {
    background-color: var(--bbq-foreground-component-color);
}

.award-list {
    background-color: var(--bbq-secondary-foreground-component-color);
}
</style>
