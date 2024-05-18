<script setup>

import { supabase } from '@/lib/supabase-client';

import MainPageHeader from '@/components/MainPageHeader.vue';
import TableHeader from '@/components/TableHeader.vue';
import EventDataRow from '@/components/EventDataRow.vue';

import '@material/web/select/outlined-select';
import '@material/web/select/select-option';

</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content">
        <!-- Filters -->
        <div class="filter-container">
            <md-outlined-select class="filter-select">
                <md-select-option v-for="filter, idx in yearFilters" v-bind:selected="idx == curYearIndex"
                    v-bind:aria-label="filter" @click="setActiveYearFilter(idx)">
                    <div slot="headline">{{ filter }}</div>
                </md-select-option>
            </md-outlined-select>
        </div>

        <!-- Rankings -->
        <div v-if="!loadingEvents || eventList.length > 0" class="ranking-container">
            <table>
                <TableHeader :column-data="tableColumns" :sorted-column-index="sortedColumnIdx" @sort="sortColumn">
                </TableHeader>
                <EventDataRow v-for="event, rank in eventList" :rank="rank + 1" :event-id="event.event_id"
                    :name="event.name" :year="event.year" :event-data="event.event_data"></EventDataRow>
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
                { "name": "Rank" },
                { "name": "Event Name" },
                { "name": "Robot BBQ", "sortable": true, "db_col": "robot_bbq" },
                { "name": "Team Attribute BBQ", "sortable": true, "db_col": "team_bbq" }
            ],
            latestYear: null,
            yearFilters: ["All"],
            curYearIndex: 0,
            sortedColumnIdx: 2
        }
    },
    created() {
        this.getAvailableYears();
        this.rankEvents();
    },
    methods: {
        async getAvailableYears() {
            this.yearFilters = ["All Years"];

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
                    this.yearFilters.push(y);
                }
            }
        },
        async getEvents() {
            // Get the active column.
            let activeDbColumn = this.tableColumns[this.sortedColumnIdx].db_col;

            this.loadingEvents = true;
            let query = supabase.from("EventData")
                .select("event_id, robot_bbq, team_bbq, Event!inner( event_id, name, year, type, type_string )")
                .order(activeDbColumn, { ascending: false });

            // Apply the year filtering.
            let year = this.yearFilters[this.curYearIndex];
            if (year != "All Years") {
                query = query.eq("Event.year", year);
            }

            const { data, error } = await query.limit(100);

            let stagedList = [];
            if (error) {
                console.log(error);
            } else {
                for (let i = 0; i < data.length; i++) {
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
                                "name": "Team Atrribute BBQ",
                                "value": data[i].team_bbq
                            }
                        ]
                    }
                    stagedList.push(eventInfo);
                }
            }

            this.eventList = stagedList;

            // Mark the event as loaded to allow the doesEventExist function to determine existence of a loaded event.
            this.loadingEvents = false;
        },
        async rankEvents() {
            await this.getEvents();
        },
        setActiveYearFilter(yearIdx) {
            this.curYearIndex = yearIdx;
            this.rankEvents();
        },
        sortColumn(col_idx) {
            if (col_idx) {
                this.sortedColumnIdx = col_idx;
                this.rankEvents();
            }
        }
    },
}
</script>

<style scoped>
.filter-container {
    display: flex;
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