<script setup>
</script>

<template>
    <div class="award" v-if="eventAvailable">
        {{ name }} @ <a v-bind:href="eventLink">{{ season }} {{ event }}</a>
    </div>
    <div class="award" v-if="!eventAvailable">
        {{ name }} @ {{ season }} {{ event }}
    </div>
</template>

<script>
export default {
    props: {
        name: {},
        season: {},
        event: {},
        event_id: {}
    },
    computed: {
        eventLink() {
            return "/event/" + this.event_id;
        },
        eventAvailable() {
            return this.event_id != null && this.event_id != undefined;
        }
    }
}
</script>

<style scoped>
.award {
    background-color: var(--bbq-foreground-component-color);
    margin-bottom: 5px;
}
</style>
