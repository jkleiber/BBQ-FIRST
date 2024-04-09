<script setup>
</script>

<template>
    <div class="team">
        {{ number }} - {{ name }} - {{ country }}
    </div>
</template>

<script>
export default {
    props: ['number', 'name', 'country']
}
</script>

<style scoped>
.team {
    background-color: var(--bbq-foreground-component-color);
    margin-bottom: 5px;
}
</style>
