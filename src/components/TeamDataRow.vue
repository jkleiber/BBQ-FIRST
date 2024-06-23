<script setup>
</script>

<template>
    <tr class="team" v-if="teamAvailable">
        <td>
            {{ rankNumberString }}
        </td>
        <td class="team-name-data">
            <a v-bind:href="teamLink">{{ year }} {{ name }}</a>
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
        teamNumber: null,
        teamData: null,
        columnData: null
    },
    computed: {
        rankNumberString() {
            if (this.rank != undefined && this.rank != null) {
                return this.rank;
            }
            return "";
        },
        teamLink() {
            return "/team/" + this.teamNumber;
        },
        teamAvailable() {
            return this.teamNumber != null && this.teamData != null;
        },
        visibleData() {
            // Default to showing all columns.
            let data = this.teamData;

            // If column data is provided, only show the visible columns based on name matching.
            if (this.columnData) {
                let cols = this.columnData.filter((col) => col.visible == true);
                let colNames = cols.map(({ name }) => name);
                data = this.teamData.filter((item) => colNames.includes(item.name));
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
.team {
    background-color: var(--bbq-foreground-component-color);
    margin-bottom: 5px;
}
</style>
