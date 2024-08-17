<script setup>

</script>

<template>
    <thead>
        <th v-for="col, i in visibleColumns" :class="headerClass(col, i)" @click="sortIfSortable(col, i)">


            <VTooltip placement="top" theme="stat-tooltip" v-if="col.label">
                <span>{{ col.name }}</span>

                <template #popper>
                    <p><strong>{{ col.name }}</strong></p>
                    <p v-html="col.label"></p>
                </template>
            </VTooltip>
            <span v-else>
                {{ col.name }}
            </span>
        </th>
    </thead>
</template>

<script>
export default {
    props: {
        columnData: null,
        sortedColumnIndex: -1
    },
    computed: {
        visibleColumns() {
            let cols = this.columnData.filter(col => col.visible == true);
            return cols;
        }
    },
    methods: {
        headerClass(col, idx) {
            if (Object.keys(col).includes('sortable')
                && col.sortable == true
                && idx == this.sortedColumnIndex) {
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
table th {
    position: sticky;
    top: 0px;
    background: var(--bbq-background-color);
}


.sorted-header {
    text-decoration: underline;
    color: var(--bbq-primary-color);
}

.sortable-header {
    text-decoration: underline;
    cursor: pointer;
}

.regular-header {
    text-decoration: none;
}
</style>
