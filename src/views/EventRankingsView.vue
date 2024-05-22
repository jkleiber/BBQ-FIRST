<script setup>

import { supabase } from '@/lib/supabase-client';

import MainPageHeader from '@/components/MainPageHeader.vue';
import TableHeader from '@/components/TableHeader.vue';
import EventDataRow from '@/components/EventDataRow.vue';

import '@material/web/select/outlined-select';
import '@material/web/select/select-option';
import '@material/web/chips/filter-chip';

</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content">
        <!-- Filters -->
        <div class="filter-container">
            <div class="filter-options">
                <h3>Year Range</h3>
                <md-outlined-select class="filter-select" v-bind:display-text="lowerYearDisplay">
                    <md-select-option v-for="filter, idx in lowerYearFilters" v-bind:selected="idx == lowerYearIndex"
                        :key="idx" :ref="lowerYearIndex" v-bind:aria-label="filter" @click="setLowerYearFilter(idx)">
                        <div slot="headline">{{ filter }}</div>
                    </md-select-option>
                </md-outlined-select>
                to
                <md-outlined-select class="filter-select" v-bind:display-text="upperYearDisplay">
                    <md-select-option v-for="filter, idx in upperYearFilters" v-bind:selected="idx == upperYearIndex"
                        v-bind:aria-label="filter" @click="setUpperYearFilter(idx)">
                        <div slot="headline">{{ filter }}</div>
                    </md-select-option>
                </md-outlined-select>
            </div>

            <div class="filter-options" style="min-width: 120px">
                <h3>Statistics</h3>
                <md-chip-set class="vertical-chip-set">
                    <md-filter-chip label="BBQ" @click="toggleStatFilter('bbq')" v-bind:selected="getStatChipStatus('bbq')"
                        class="vertical-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="SAUCE" @click="toggleStatFilter('sauce')"
                        v-bind:selected="getStatChipStatus('sauce')" class="vertical-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="BRIQUETTE" @click="toggleStatFilter('briquette')"
                        v-bind:selected="getStatChipStatus('briquette')" class="vertical-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="RIBS" @click="toggleStatFilter('ribs')"
                        v-bind:selected="getStatChipStatus('ribs')" class="vertical-chip-set-chip"></md-filter-chip>
                </md-chip-set>
            </div>

            <div class="filter-options" style="min-width: 141px">
                <h3>Award Types</h3>
                <md-chip-set class="vertical-chip-set">
                    <md-filter-chip label="Robot" @click="toggleTypeFilter('robot')"
                        v-bind:selected="getTypeChipStatus('robot')" class="vertical-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="Team Attribute" @click="toggleTypeFilter('team')"
                        v-bind:selected="getTypeChipStatus('team')" class="vertical-chip-set-chip"></md-filter-chip>
                </md-chip-set>
            </div>
        </div>

        <!-- Rankings -->
        <div v-if="!loadingEvents || eventList.length > 0" class="ranking-container">
            <table>
                <TableHeader :column-data="tableColumns" :sorted-column-index="sortedColumnIdx" @sort="sortColumn">
                </TableHeader>
                <tbody class="scrollable-table-body">
                    <EventDataRow v-for="event, rank in eventList" :rank="rank + 1" :event-id="event.event_id"
                        :name="event.name" :year="event.year" :event-data="event.event_data" :column-data="tableColumns">
                    </EventDataRow>
                </tbody>
            </table>
        </div>
        <div v-else-if="loadingEvents && eventList.length == 0">
            Loading Events...
        </div>
        <div v-else>
            No matching events.
        </div>

    </div>
</template>

<script>

export default {
    data() {
        return {
            loadingEvents: false,
            eventList: [],
            tableColumns: [
                { "name": "Rank", visible: true, stat: 'info', type: 'info' },
                { "name": "Event Name", visible: true, stat: 'info', type: 'info' },
                { "name": "Robot BBQ", "sortable": true, "db_col": "robot_bbq", visible: true, stat: 'bbq', type: 'robot' },
                { "name": "Team Attribute BBQ", "sortable": true, "db_col": "team_bbq", visible: true, stat: 'bbq', type: 'team' },
                { "name": "Robot SAUCE", "sortable": true, "db_col": "robot_sauce", visible: true, stat: 'sauce', type: 'robot' },
                { "name": "Team Attribute SAUCE", "sortable": true, "db_col": "team_sauce", visible: true, stat: 'sauce', type: 'team' },
                { "name": "Robot BRIQUETTE", "sortable": true, "db_col": "robot_briquette", visible: true, stat: 'briquette', type: 'robot' },
                { "name": "Team Attribute BRIQUETTE", "sortable": true, "db_col": "team_briquette", visible: true, stat: 'briquette', type: 'team' },
                { "name": "Robot RIBS", "sortable": true, "db_col": "robot_ribs", visible: true, stat: 'ribs', type: 'robot' },
                { "name": "Team Attribute RIBS", "sortable": true, "db_col": "team_ribs", visible: true, stat: 'ribs', type: 'team' }
            ],
            latestYear: null,
            upperYearFilters: [],
            lowerYearFilters: [],
            lowerYearIndex: 0,
            upperYearIndex: 0,
            sortedColumnIdx: 2,
            visibilityMap: {
                'stats': {
                    'info': true,
                    'bbq': true,
                    'sauce': true,
                    'briquette': true,
                    'ribs': true,
                },
                'types': {
                    'info': true,
                    'robot': true,
                    'team': true
                }
            }
        }
    },
    created() {
        this.initComponent();
    },
    computed: {
        lowerYearDisplay() {
            if (this.lowerYearIndex < this.lowerYearFilters.length) {
                return this.lowerYearFilters[this.lowerYearIndex];
            }
            return ""
        },
        upperYearDisplay() {
            if (this.upperYearIndex < this.upperYearFilters.length) {
                return this.upperYearFilters[this.upperYearIndex];
            }
            return ""
        },
    },
    methods: {
        async initComponent() {
            await this.getAvailableYears();
            this.rankEvents();
        },
        async getAvailableYears() {
            this.upperYearFilters = [];

            // Get the latest event year in the database.
            const { data, error } = await supabase.from("Event").select().order("year", { ascending: false }).limit(1);
            if (error) {
                console.log(error);
                return;
            }

            // Set the latest year.
            this.latestYear = data[0].year;

            // Create a list of years going back to 1992, which is the first competition year.
            const kFirstYear = 1992;
            if (this.latestYear) {
                for (let y = this.latestYear; y >= kFirstYear; y--) {
                    this.upperYearFilters.push(y);
                }
            }

            // Reverse the lower year filter to count up.
            this.lowerYearFilters = this.upperYearFilters.slice().reverse();
        },
        async getEvents() {
            // Get the active column. The active column must be visible.
            let visibleCols = this.tableColumns.filter(col => col.visible == true);
            let activeDbColumn = visibleCols[this.sortedColumnIdx].db_col;

            this.loadingEvents = true;
            let query = supabase.from("EventData")
                .select("*, Event!inner( event_id, name, year, type, type_string )")
                .order(activeDbColumn, { ascending: false });

            // Apply the year filtering.
            let upperYear = this.upperYearFilters[this.upperYearIndex];
            if (upperYear) {
                query = query.lte("Event.year", upperYear);
            }

            let lowerYear = this.lowerYearFilters[this.lowerYearIndex];
            if (lowerYear) {
                query = query.gte("Event.year", lowerYear);
            }

            const { data, error } = await query.limit(100);

            let stagedList = [];
            if (error) {
                console.log(error);
            } else {
                for (let i = 0; i < data.length; i++) {

                    let dataValid = (data[i].robot_bbq
                        && data[i].team_bbq
                        && data[i].robot_sauce
                        && data[i].team_sauce
                        && data[i].robot_briquette
                        && data[i].team_briquette
                        && data[i].robot_ribs
                        && data[i].team_ribs);

                    let eventInfo = {
                        "name": data[i].Event.name,
                        "year": data[i].Event.year,
                        "event_id": data[i].event_id,
                        "event_data": [
                            {
                                "name": "Robot BBQ",
                                "value": data[i].robot_bbq
                            },
                            {
                                "name": "Team Attribute BBQ",
                                "value": data[i].team_bbq
                            },
                            {
                                "name": "Robot SAUCE",
                                "value": data[i].robot_sauce
                            },
                            {
                                "name": "Team Attribute SAUCE",
                                "value": data[i].team_sauce
                            },
                            {
                                "name": "Robot BRIQUETTE",
                                "value": data[i].robot_briquette
                            },
                            {
                                "name": "Team Attribute BRIQUETTE",
                                "value": data[i].team_briquette
                            },
                            {
                                "name": "Robot RIBS",
                                "value": data[i].robot_ribs
                            },
                            {
                                "name": "Team Attribute RIBS",
                                "value": data[i].team_ribs
                            }
                        ]
                    }

                    if (dataValid) {
                        stagedList.push(eventInfo);
                    }
                }
            }

            this.eventList = stagedList;

            // Mark the event as loaded to allow the doesEventExist function to determine existence of a loaded event.
            this.loadingEvents = false;
        },
        async rankEvents() {
            await this.getEvents();
        },
        setLowerYearFilter(yearIdx) {
            this.lowerYearIndex = yearIdx;
            this.rankEvents();
        },
        setUpperYearFilter(yearIdx) {
            this.upperYearIndex = yearIdx;
            this.rankEvents();
        },
        sortColumn(col_idx) {
            if (col_idx) {
                this.sortedColumnIdx = col_idx;
                this.rankEvents();
            }
        },
        toggleStatFilter(stat) {
            if (Object.keys(this.visibilityMap.stats).includes(stat)) {
                this.visibilityMap.stats[stat] = !this.visibilityMap.stats[stat];
                this.updateColumnVisibility();
            }
        },
        toggleTypeFilter(stat_type) {
            if (Object.keys(this.visibilityMap.types).includes(stat_type)) {
                this.visibilityMap.types[stat_type] = !this.visibilityMap.types[stat_type];
                this.updateColumnVisibility();
            }
        },
        getStatChipStatus(stat) {
            if (Object.keys(this.visibilityMap.stats).includes(stat)) {
                return this.visibilityMap.stats[stat];
            }
            return false;
        },
        getTypeChipStatus(stat_type) {
            if (Object.keys(this.visibilityMap.types).includes(stat_type)) {
                return this.visibilityMap.types[stat_type];
            }
            return false;
        },
        updateColumnVisibility() {
            for (var i in this.tableColumns) {
                let colType = this.tableColumns[i].type;
                let colStat = this.tableColumns[i].stat;

                if (Object.keys(this.visibilityMap.types).includes(colType) && Object.keys(this.visibilityMap.stats).includes(colStat)) {
                    let visibility = (this.visibilityMap.types[colType]) && (this.visibilityMap.stats[colStat]);
                    this.tableColumns[i].visible = visibility;
                }
            }

            // Reset the sorted column index since we don't really know what got removed.
            // TODO: make this smarter and retain the sorted column if possible.
            this.sortedColumnIdx = 2;

            // re-rank events
            this.rankEvents();
        }
    },
}
</script>

<style scoped>
.filter-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.filter-options {
    display: flex;
    flex-direction: column;
    width: fit-content;
    margin-left: 20px;
    margin-right: 20px;
}

.vertical-chip-set {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
}

.vertical-chip-set-chip {
    margin-bottom: 5px;
}

.ranking-container {
    flex: 1 auto;
    overflow: auto;
    max-height: 100%;
}

.filter-select {
    /* TODO: figure out what mechanism makes it so this number can't be set smaller. 
    This is a good size, but if we ever want to make this filter select smaller we 
    should figure out what is controlling the size */
    max-width: 210px;
}

.v-centered-button {
    top: 50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
    height: fit-content;
}
</style>