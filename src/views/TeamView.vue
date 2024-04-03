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
    <div class="main-content">

        <div class="team-header-container">
            <div class="team-info-container" v-if="doesTeamExist()">
                <h1 class="team-number-header"> {{ teamTitleText }} </h1>

                <h3>Robot Performance Banners: {{ numRobotAwards }}</h3>
                <h3>Team Attribute Banners: {{ numTeamAwards }}</h3>
            </div>

            <form class="team-search-form">
                <md-outlined-text-field class="team-text-field" type="number" label="Team Number" v-model="teamNumber"
                    @keydown.enter="submit()"></md-outlined-text-field>
                <md-filled-button class="v-centered-button" type="submit"
                    @click.stop.prevent="submit()">Submit</md-filled-button>
            </form>
        </div>


        <!-- Show team information if it exists -->
        <div class="team-container" v-if="doesTeamExist()">
            <md-tabs>
                <md-primary-tab v-for="tab in tabs" @click="changeTab(tab)" :key="tab.name">
                    <md-icon slot="icon">{{ tab.icon }}</md-icon>
                    {{ tab.name }}
                </md-primary-tab>
            </md-tabs>

            <div id="robot-panel" class="award-tab" role="tabpanel" aria-labelledby="robot-tab" v-if="isActive(0)">
                <md-list>
                    <AwardItem v-for="award in robotAwardsList" :name="award.name" :season="award.season"
                        :event="award.event">
                    </AwardItem>
                </md-list>
            </div>

            <div id="team-panel" class="award-tab" role="tabpanel" aria-labelledby="team-tab" v-if="isActive(1)">
                <md-list>
                    <AwardItem v-for="award in teamAwardsList" :name="award.name" :season="award.season"
                        :event="award.event">
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
            teamNumber: this.$route.params.team_number,
            teamString: "",
            tabs: [
                { id: 0, name: "Robot Performance", icon: "smart_toy" },
                { id: 1, name: "Team Attribute", icon: "diversity_3" }
            ],
            activeTab: 0,
            robotAwardsList: [],
            teamAwardsList: []
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
            }

            return this.teamString;
        },
        numTeamAwards() {
            return this.teamAwardsList.length;
        },
        numRobotAwards() {
            return this.robotAwardsList.length;
        }
    },
    methods: {
        submit() {
            // Process the team number into a string for the URL and route.
            let routeString = this.teamNumber;
            if (routeString == null) {
                routeString = "";
            }

            // Update the URL to match the team number.
            history.pushState(
                {},
                null,
                "/team/" + routeString
            );

            // Go to the team page.
            this.$router.push("/team/" + routeString);

            // Update the team information.
            this.teamChange();
        },
        changeTab(tab) {
            this.activeTab = tab.id;
        },
        isActive(id) {
            return id == this.activeTab;
        },
        doesTeamExist() {
            return this.$route.params.team_number != null && this.$route.params.team_number > 0;
        },
        getAwardList(data) {
            var awardList = [];
            for (let i = 0; i < data.length; i++) {
                let a = data[i];
                var award = {
                    "name": a['name'],
                    "season": a.season,
                    "event": a.event_id
                }
                awardList.push(award);
            }

            return awardList;
        },
        async getTeamAwards() {
            const { data, error } = await supabase.from("BlueBanner")
                .select()
                .eq("team_number", this.teamNumber)
                .eq("type", "Team");
            var awardList = this.getAwardList(data);

            console.log(awardList)

            this.teamAwardsList = awardList
        },
        async getRobotAwards() {
            const { data, error } = await supabase.from("BlueBanner")
                .select()
                .eq("team_number", this.teamNumber)
                .eq("type", "Robot");
            var awardList = this.getAwardList(data);

            this.robotAwardsList = awardList
        },
        teamChange() {
            this.getRobotAwards();
            this.getTeamAwards();
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
    width: 50%;
    border: var(--bbq-primary-color) 2px solid;
    border-radius: 5px;
}

.team-container {
    display: flex;
    flex-flow: column;
    flex: 1;
    height: 60vh;
}

.award-tab {
    /* position: absolute; */
    flex: 1;
    overflow: scroll;
}

.team-text-field {
    align-items: center;
}

form.team-search-form {
    display: flex;
    height: 100px;
}
</style>