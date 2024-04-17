<script setup>

</script>

<template>
    <thead>
        <th v-for="col, i in columnData" :class="headerClass(col)" @click="sortIfSortable(col, i)">
            {{ col.name }}
        </th>
    </thead>
</template>

<script>
export default {
    props: {
        columnData: null
    },
    methods: {
        headerClass(col) {
            if (Object.keys(col).includes('sortable')
                && col.sortable == true
                && Object.keys(col).includes('sorted')
                && col.sorted == true) {
                return "sorted-header"
            }
            else if (Object.keys(col).includes('sortable') && col.sortable == true) {
                return "sortable-header";
            }
            return "regular-header"
        },
        sortIfSortable(col, col_idx) {
            if (Object.keys(col).includes('sortable') && col.sortable) {
                this.$emit('sort', col_idx);
            }
        }
    }
}
</script>

<style scoped>
.stat-row {
    display: flex;
    flex-direction: row;
}

.sorted-header {
    text-decoration: underline;
    background-color: var(--bbq-primary-color);
    color: var(--bbq-accent-color);
}

.sortable-header {
    text-decoration: underline;
    cursor: pointer;
}

.regular-header {
    text-decoration: none;
}
</style>
