<script setup>

// Define this component's inputs.
const props = defineProps(['name', 'season', 'event'])

</script>

<template>
    <div class="award">
        {{ props.season }} - {{ props.event }} - {{ props.name }}
    </div>
</template>

<script>
export default {

}
</script>

<style scoped>
.award {
    background-color: var(--bbq-foreground-component-color);
    /* height: 100px; */
    /* margin-top: 5px; */
    margin-bottom: 5px;
}
</style>
