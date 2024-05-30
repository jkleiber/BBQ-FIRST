<script setup>
</script>

<template>
    <tr class="event" v-if="eventAvailable">
        <td>
            {{ rankNumberString }}
        </td>
        <td class="event-name-data">
            <a v-bind:href="eventLink">{{ year }} {{ name }}</a>
        </td>
        <td v-for="stat in visibleData">
            {{ valueDisplay(stat) }}
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
        eventData: null,
        columnData: null
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
        },
        visibleData() {
            // Default to showing all columns.
            let data = this.eventData;

            // If column data is provided, only show the visible columns based on name matching.
            if (this.columnData) {
                let cols = this.columnData.filter((col) => col.visible == true);
                let colNames = cols.map(({ name }) => name);
                data = this.eventData.filter((item) => colNames.includes(item.name));
            }
            return data;
        }
    },
    methods: {
        valueDisplay(stat) {
            if (stat && stat.value != null) {
                return stat.value.toFixed(4);
            }
            return "N/A";
        }
    }
}
</script>

<style scoped>
.event {
    background-color: var(--bbq-foreground-component-color);
    margin-bottom: 5px;
}

/* .event-name-data {
    max-width: 50%;
} */
</style>
