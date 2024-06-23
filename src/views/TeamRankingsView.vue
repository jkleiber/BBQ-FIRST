<script setup>

import { supabase } from '@/lib/supabase-client';

import MainPageHeader from '@/components/MainPageHeader.vue';
import TableHeader from '@/components/TableHeader.vue';
import TeamDataRow from '@/components/TeamDataRow.vue';

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
                <h3>Latest Rookie Year</h3>
                <md-outlined-select class="filter-select" v-bind:display-text="rookieYearDisplay">
                    <md-select-option v-for="filter, idx in rookieYearFilters" v-bind:selected="idx == rookieYearIndex"
                        :key="idx" :ref="rookieYearIndex" v-bind:aria-label="filter" @click="setRookieYearFilter(idx)">
                        <div slot="headline">{{ filter }}</div>
                    </md-select-option>
                </md-outlined-select>
            </div>

            <div class="filter-options" style="min-width: 120px">
                <h3>Statistics</h3>
                <md-chip-set class="horizontal-chip-set">
                    <md-filter-chip label="Total" @click="toggleChip('count')" v-bind:selected="getChipStatus('count')"
                        v-bind:disabled="!chipEnabled('count')" class="horizontal-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="BBQ" @click="toggleChip('bbq')" v-bind:selected="getChipStatus('bbq')"
                        v-bind:disabled="!chipEnabled('bbq')" class="horizontal-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="SAUCE" @click="toggleChip('sauce')" v-bind:selected="getChipStatus('sauce')"
                        v-bind:disabled="!chipEnabled('sauce')" class="horizontal-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="BRIQUETTE" @click="toggleChip('briquette')"
                        v-bind:selected="getChipStatus('briquette')" v-bind:disabled="!chipEnabled('briquette')"
                        class="vertical-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="RIBS" @click="toggleChip('ribs')" v-bind:selected="getChipStatus('ribs')"
                        v-bind:disabled="!chipEnabled('ribs')" class="horizontal-chip-set-chip"></md-filter-chip>
                </md-chip-set>
            </div>

            <div class="filter-options" style="min-width: 141px">
                <h3>Award Types</h3>
                <md-chip-set class="horizontal-chip-set">
                    <md-filter-chip label="Robot" @click="toggleChip('robot')" v-bind:selected="getChipStatus('robot')"
                        v-bind:disabled="!chipEnabled('robot')" class="horizontal-chip-set-chip"></md-filter-chip>
                    <md-filter-chip label="Team Attribute" @click="toggleChip('team')"
                        v-bind:selected="getChipStatus('team')" v-bind:disabled="!chipEnabled('team')"
                        class="horizontal-chip-set-chip"></md-filter-chip>
                </md-chip-set>
            </div>
        </div>

        <!-- Rankings -->
        <div v-if="!loadingTeams || teamList.length > 0" class="ranking-container">
            <table>
                <TableHeader :column-data="tableColumns" :sorted-column-index="sortedColumnIdx" @sort="sortColumn">
                </TableHeader>
                <tbody class="scrollable-table-body">
                    <TeamDataRow v-for="team, rank in teamList" :rank="rank + 1" :team-number="team.team_number"
                        :name="team.name" :year="team.year" :team-data="team.team_data" :column-data="tableColumns">
                    </TeamDataRow>
                </tbody>
            </table>
        </div>
        <div v-else-if="loadingTeams && teamList.length == 0">
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
            loadingTeams: false,
            teamList: [],
            tableColumns: [
                { "name": "Rank", visible: true, stat: 'info', type: 'info' },
                { "name": "Team", visible: true, stat: 'info', type: 'info' },
                { "name": "Robot Banners", "sortable": true, "db_col": "robot_banners", visible: true, stat: 'count', type: 'robot' },
                { "name": "Team Attribute Banners", "sortable": true, "db_col": "team_banners", visible: true, stat: 'count', type: 'team' },
                { "name": "Robot BBQ", "sortable": true, "db_col": "robot_bbq", visible: true, stat: 'bbq', type: 'robot' },
                { "name": "Team Attribute BBQ", "sortable": true, "db_col": "team_bbq", visible: true, stat: 'bbq', type: 'team' },
                { "name": "Robot SAUCE", "sortable": true, "db_col": "robot_sauce", visible: true, stat: 'sauce', type: 'robot' },
                { "name": "Team Attribute SAUCE", "sortable": true, "db_col": "team_sauce", visible: true, stat: 'sauce', type: 'team' },
                { "name": "Robot BRIQUETTE", "sortable": true, "db_col": "robot_briquette", visible: true, stat: 'briquette', type: 'robot' },
                { "name": "Team Attribute BRIQUETTE", "sortable": true, "db_col": "team_briquette", visible: true, stat: 'briquette', type: 'team' },
                { "name": "Robot RIBS", "sortable": true, "db_col": "robot_ribs", visible: true, stat: 'ribs', type: 'robot' },
                { "name": "Team Attribute RIBS", "sortable": true, "db_col": "team_ribs", visible: true, stat: 'ribs', type: 'team' }
            ],
            sortedColumnIdx: 2,
            enabledMap: {
                'stats': {
                    'info': true,
                    'count': true,
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
            },
            visibilityMap: {
                'stats': {
                    'info': true,
                    'count': true,
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
            },
            latestYear: null,
            rookieYearFilters: [],
            rookieYearIndex: 0
        }
    },
    created() {
        this.initComponent();
    },
    computed: {
        rookieYearDisplay() {
            if (this.rookieYearIndex < this.rookieYearFilters.length) {
                return this.rookieYearFilters[this.rookieYearIndex];
            }
            return ""
        },
    },
    methods: {
        async initComponent() {
            await this.getAvailableYears();
            this.rankTeams();
        },
        async getAvailableYears() {
            this.upperYearFilters = [];

            // Get the latest event year in the database.
            const { data, error } = await supabase.from("Team")
                .select().not("rookie_year", "is", null)
                .order("rookie_year", { ascending: false })
                .limit(1);
            if (error) {
                console.log(error);
                return;
            }

            // Set the latest year.
            this.latestYear = data[0].rookie_year;

            // Create a list of years going back to 1992, which is the first competition year.
            const kFirstYear = 1992;
            if (this.latestYear) {
                for (let y = this.latestYear; y >= kFirstYear; y--) {
                    this.rookieYearFilters.push(y);
                }
            }

            this.rookieYearIndex = 0;
        },
        async getTeams() {
            // Get the active column. The active column must be visible.
            let visibleCols = this.tableColumns.filter(col => col.visible == true);
            let activeDbColumn = visibleCols[this.sortedColumnIdx].db_col;

            this.loadingTeams = true;
            let query = supabase.from("TeamData")
                .select("*, Team!inner( team_number, nickname, rookie_year )")
                .not(activeDbColumn, "is", null)
                .order(activeDbColumn, { ascending: false });

            // Apply the year filtering.
            let maxRookieYear = this.rookieYearFilters[this.rookieYearIndex];
            if (maxRookieYear) {
                query = query.lte("Team.rookie_year", maxRookieYear);
            }

            const { data, error } = await query.limit(100);

            let stagedList = [];
            if (error) {
                console.log(error);
            } else {
                for (let i = 0; i < data.length; i++) {

                    let dataValid = (data[i].robot_bbq !== null
                        && data[i].team_bbq !== null
                        && data[i].robot_sauce !== null
                        && data[i].team_sauce !== null
                        && data[i].robot_briquette !== null
                        && data[i].team_briquette !== null
                        && data[i].robot_ribs !== null
                        && data[i].team_ribs !== null);

                    let teamInfo = {
                        "name": "Team " + data[i].Team.team_number + ": " + data[i].Team.nickname,
                        "team_number": data[i].Team.team_number,
                        "team_data": [
                            {
                                "name": "Robot Banners",
                                "value": data[i].robot_banners
                            },
                            {
                                "name": "Team Attribute Banners",
                                "value": data[i].team_banners
                            },
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
                        stagedList.push(teamInfo);
                    }
                }
            }

            this.teamList = stagedList;

            // Mark the teams as loaded to allow the doesTeamsExist function to determine existence of a loaded event.
            this.loadingTeams = false;
        },
        async rankTeams() {
            await this.getTeams();
        },
        setRookieYearFilter(yearIdx) {
            this.rookieYearIndex = yearIdx;
            this.rankTeams();
        },
        sortColumn(col_idx) {
            if (col_idx) {
                this.sortedColumnIdx = col_idx;
                this.rankTeams();
            }
        },
        chipEnabled(chip) {
            return (Object.keys(this.enabledMap.stats).includes(chip)
                && this.enabledMap.stats[chip] === true)
                || (Object.keys(this.enabledMap.types).includes(chip)
                    && this.enabledMap.types[chip] === true);
        },
        chipVisible(chip) {
            return (Object.keys(this.visibilityMap.stats).includes(chip)
                && this.visibilityMap.stats[chip] == true)
                || (Object.keys(this.visibilityMap.types).includes(chip)
                    && this.visibilityMap.types[chip] == true)
        },
        chipToggleable(chip) {
            return this.chipEnabled(chip);
        },
        toggleChip(chip) {
            if (!this.chipToggleable(chip)) {
                return;
            }

            if (Object.keys(this.visibilityMap.stats).includes(chip)) {
                this.visibilityMap.stats[chip] = !this.visibilityMap.stats[chip];
                this.updateColumnVisibility();
            } else if (Object.keys(this.visibilityMap.types).includes(chip)) {
                this.visibilityMap.types[chip] = !this.visibilityMap.types[chip];
                this.updateColumnVisibility();
            }
        },
        getChipStatus(chip) {
            if (!this.chipToggleable(chip)) {
                return false;
            }

            if (Object.keys(this.visibilityMap.stats).includes(chip)) {
                return this.visibilityMap.stats[chip];
            } else if (Object.keys(this.visibilityMap.types).includes(chip)) {
                return this.visibilityMap.types[chip];
            }

            return false;
        },
        updateColumnVisibility() {
            for (var i in this.tableColumns) {
                let colType = this.tableColumns[i].type;
                let colStat = this.tableColumns[i].stat;

                if (Object.keys(this.visibilityMap.types).includes(colType)
                    && Object.keys(this.visibilityMap.stats).includes(colStat)
                    && Object.keys(this.enabledMap.types).includes(colType)
                    && Object.keys(this.enabledMap.stats).includes(colStat)) {
                    let visibility = (this.visibilityMap.types[colType])
                        && (this.visibilityMap.stats[colStat])
                        && (this.enabledMap.types[colType])
                        && (this.enabledMap.stats[colStat]);
                    this.tableColumns[i].visible = visibility;
                }
            }

            // Reset the sorted column index since we don't really know what got removed.
            // TODO: make this smarter and retain the sorted column if possible.
            this.sortedColumnIdx = 2;

            // re-rank events
            this.rankTeams();
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

.horizontal-chip-set {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.horizontal-chip-set-chip {
    margin-right: 5px;
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