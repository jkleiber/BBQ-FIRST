<script setup>

</script>

<template>
    <thead>
        <th v-for="colName in columns">
            {{ colName }}
        </th>
    </thead>
</template>

<script>
export default {
    props: ['columns']
}
</script>

<style scoped>
.stat-row {
    display: flex;
    flex-direction: row;
}
</style>
