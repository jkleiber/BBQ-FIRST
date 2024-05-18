<script setup>

import { supabase } from '@/lib/supabase-client';

import MainPageHeader from '@/components/MainPageHeader.vue';
import AwardItem from '@/components/AwardItem.vue';

import '@material/web/tabs/tabs';
import '@material/web/tabs/primary-tab';
import '@material/web/icon/icon';
import '@material/web/textfield/outlined-text-field';
import '@material/web/button/filled-button';
import '@material/web/list/list';
import '@material/web/list/list-item';

</script>

<template>
    <MainPageHeader></MainPageHeader>


    <!-- Show team information if it exists -->
    <div class="main-content" v-if="doesTeamExist()">

        <div class="team-header-container">
            <div class="team-info-container" v-if="doesTeamExist()">
                <h1 class="team-number-header"> {{ teamTitleText }} </h1>

                <h3>{{ teamDurationString }}</h3>
                <h3>Robot Performance Banners: {{ numRobotAwards }}</h3>
                <h3>Team Attribute Banners: {{ numTeamAwards }}</h3>
            </div>
        </div>

        <div class="team-container">
            <md-tabs>
                <md-primary-tab v-for="tab in tabs" @click="changeTab(tab)" :key="tab.name">
                    <md-icon slot="icon">{{ tab.icon }}</md-icon>
                    {{ tab.name }}
                </md-primary-tab>
            </md-tabs>

            <div id="robot-panel" class="award-tab" role="tabpanel" aria-labelledby="robot-tab" v-if="isActive(0)">
                <md-list>
                    <AwardItem v-for="award in robotAwardsList" :name="award.name" :season="award.season"
                        :event="award.event" :event_id="award.event_id">
                    </AwardItem>
                </md-list>
            </div>

            <div id="team-panel" class="award-tab" role="tabpanel" aria-labelledby="team-tab" v-if="isActive(1)">
                <md-list>
                    <AwardItem v-for="award in teamAwardsList" :name="award.name" :season="award.season"
                        :event="award.event" :event_id="award.event_id">
                    </AwardItem>
                </md-list>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            teamString: "",
            tabs: [
                { id: 0, name: "Robot Performance", icon: "smart_toy" },
                { id: 1, name: "Team Attribute", icon: "diversity_3" }
            ],
            activeTab: 0,
            robotAwardsList: [],
            teamAwardsList: [],
            nickname: "",
            rookieYear: null
        }
    },
    created() {
        this.teamChange();
    },
    computed: {
        teamTitleText() {
            this.teamString = "";
            if (this.$route.params.team_number != null) {
                this.teamString = "Team " + this.$route.params.team_number;

                if (this.nickname != "") {
                    this.teamString += " - " + this.nickname;
                }
            }

            return this.teamString;
        },
        numTeamAwards() {
            return this.teamAwardsList.length;
        },
        numRobotAwards() {
            return this.robotAwardsList.length;
        },
        teamDurationString() {
            let rookieString = "";
            if (this.rookieYear != null) {
                rookieString = "First Season: " + this.rookieYear;
            }
            return rookieString;
        },
    },
    methods: {
        changeTab(tab) {
            this.activeTab = tab.id;
        },
        isActive(id) {
            return id == this.activeTab;
        },
        doesTeamExist() {
            return this.$route.params.team_number != null && this.$route.params.team_number > 0;
        },
        async getAwards() {
            let teamNumber = this.$route.params.team_number;
            let teamAwards = [];
            let robotAwards = [];
            const { data, error } = await supabase.from("BlueBanner")
                .select("name, team_number, type, Event( event_id, name, year )")
                .eq("team_number", teamNumber);

            // If the data is not null, populate the award lists.
            // If the data is null, the award lists will be set to empty. 
            if (data) {
                for (let i = 0; i < data.length; i++) {
                    let a = data[i];
                    var award = {
                        "name": a.name,
                        "season": a.Event.year,
                        "event": a.Event.name,
                        "event_id": a.Event.event_id
                    }

                    if (data[i].type == "Robot") {
                        robotAwards.push(award);
                    } else if (data[i].type == "Team") {
                        teamAwards.push(award);
                    }
                }
            }
            this.teamAwardsList = teamAwards;
            this.robotAwardsList = robotAwards;
        },
        async getTeamInfo() {
            let teamNumber = this.$route.params.team_number;
            const { data, error } = await supabase.from("Team").select("*").eq("team_number", teamNumber);

            if (data) {
                this.nickname = data[0].nickname;
                this.rookieYear = data[0].rookie_year;
            }
        },
        teamChange() {
            if (this.doesTeamExist()) {
                this.getTeamInfo();
                this.getAwards();
            }
        }
    },
}
</script>

<style scoped>
div.team-header-container {
    display: flex;
    margin-bottom: 20px;
}

div.team-info-container {
    width: 100%;
    border: var(--bbq-primary-color) 2px solid;
    border-radius: 5px;
}

.team-container {
    display: flex;
    flex-flow: column;
    flex: 1;
}

.award-tab {
    /* position: absolute; */
    flex: 1;
    overflow: auto;
}

.team-text-field {
    align-items: center;
}

form.team-search-form {
    display: flex;
    height: 100px;
}
</style>