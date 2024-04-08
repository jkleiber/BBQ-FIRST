<script setup>

</script>

<template>
    <div class="stat-row">
        <div v-for="stat in stats" class="stat-cell">
            <div class="stat-name">
                {{ stat.name }}
            </div>
            <div class="stat-value">
                {{ stat.value }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['stats']
}
</script>

<style scoped>
.stat-row {
    display: flex;
    flex-direction: row;
}
</style>
