<script setup>
</script>

<template>
    <tr class="event" v-if="eventAvailable">
        <td>
            {{ rankNumberString }}
        </td>
        <td>
            <a v-bind:href="eventLink">{{ year }} {{ name }}</a>
        </td>
        <td v-for="stat in eventData">
            {{ stat.value.toFixed(4) }}
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        rank: null,
        name: null,
        year: null,
        eventId: null,
        eventData: null
    },
    computed: {
        rankNumberString() {
            if (this.rank != undefined && this.rank != null) {
                return this.rank;
            }
            return "";
        },
        eventLink() {
            return "/event/" + this.eventId;
        },
        eventAvailable() {
            return this.eventId != null && this.eventData != null;
        }
    }
}
</script>

<style scoped>
.event {
    background-color: var(--bbq-foreground-component-color);
    margin-bottom: 5px;
}
</style>
