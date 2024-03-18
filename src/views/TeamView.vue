<script setup>
import MainPageHeader from '@/components/MainPageHeader.vue';

import '@/assets/bbq.css';

import '@material/web/tabs/tabs';
import '@material/web/tabs/primary-tab';
import '@material/web/icon/icon';

</script>

<template>
    <MainPageHeader></MainPageHeader>
    <div class="main-content">
        <form>
            <input type="number" name="teamNumber" v-model="teamNumber">
            <button type="submit" @click.stop.prevent="submit()">Submit</button>
        </form>

        <!-- Show team information if it exists -->
        <h1> {{ teamTitleText }} </h1>


        <md-tabs>
            <md-primary-tab v-for="tab in tabs" @click="changeTab(tab)" :key="tab.name">
                <md-icon slot="icon">{{ tab.icon }}</md-icon>
                {{ tab.name }}
            </md-primary-tab>
        </md-tabs>

        <div id="robot-panel" role="tabpanel" aria-labelledby="robot-tab" v-if="isActive(0)">
            Robot Awards
        </div>

        <div id="team-panel" role="tabpanel" aria-labelledby="team-tab" v-if="isActive(1)">
            Team Awards
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            teamNumber: this.$route.params.team_number,
            teamString: null,
            tabs: [
                { id: 0, name: "Robot Performance", icon: "smart_toy" },
                { id: 1, name: "Team Attribute", icon: "diversity_3" }
            ],
            activeTab: 0
        }
    },
    computed: {
        teamTitleText() {
            this.teamString = "";
            if (this.$route.params.team_number != null) {
                this.teamString = "Team " + this.$route.params.team_number;
            }

            return this.teamString;
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
        },
        changeTab(tab) {
            this.activeTab = tab.id;
        },
        isActive(id) {
            return id == this.activeTab;
        }
    },
}
</script>