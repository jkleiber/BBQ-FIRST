<script setup>

// Define this component's inputs.
const props = defineProps(['name', 'season', 'event'])

</script>

<template>
    <div class="award">
        {{ props.name }} @ {{ props.season }} {{ props.event }}
    </div>
</template>

<script>
export default {

}
</script>

<style scoped>
.award {
    background-color: var(--bbq-foreground-component-color);
    margin-bottom: 5px;
}
</style>
