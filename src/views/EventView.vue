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

        <div class="event-header-container">
            <div class="event-info-container" v-if="doeseventExist()">
                <h1 class="event-number-header"> {{ eventTitleText }} </h1>

                <h3>Robot Performance Banners: {{ numRobotAwards }}</h3>
                <h3>event Attribute Banners: {{ numeventAwards }}</h3>
            </div>

            <form class="event-search-form">
                <md-outlined-text-field class="event-text-field" type="text" label="Event Name/Code" v-model="eventNumber"
                    @keydown.enter="submit()"></md-outlined-text-field>
                <md-filled-button class="v-centered-button" type="submit"
                    @click.stop.prevent="submit()">Submit</md-filled-button>
            </form>
        </div>


        <!-- Show event information if it exists -->
        <div class="event-container" v-if="doeseventExist()">
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

            <div id="event-panel" class="award-tab" role="tabpanel" aria-labelledby="event-tab" v-if="isActive(1)">
                <md-list>
                    <AwardItem v-for="award in eventAwardsList" :name="award.name" :season="award.season"
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
            eventNumber: this.$route.params.event_number,
            eventString: "",
            tabs: [
                { id: 0, name: "Robot Performance", icon: "smart_toy" },
                { id: 1, name: "event Attribute", icon: "diversity_3" }
            ],
            activeTab: 0,
            robotAwardsList: [],
            eventAwardsList: []
        }
    },
    created() {
        this.eventChange();
    },
    computed: {
        eventTitleText() {
            this.eventString = "";
            if (this.$route.params.event_number != null) {
                this.eventString = "event " + this.$route.params.event_number;
            }

            return this.eventString;
        },
        numeventAwards() {
            return this.eventAwardsList.length;
        },
        numRobotAwards() {
            return this.robotAwardsList.length;
        }
    },
    methods: {
        submit() {
            // Process the event number into a string for the URL and route.
            let routeString = this.eventNumber;
            if (routeString == null) {
                routeString = "";
            }

            // Update the URL to match the event number.
            history.pushState(
                {},
                null,
                "/event/" + routeString
            );

            // Go to the event page.
            this.$router.push("/event/" + routeString);

            // Update the event information.
            this.eventChange();
        },
        changeTab(tab) {
            this.activeTab = tab.id;
        },
        isActive(id) {
            return id == this.activeTab;
        },
        doeseventExist() {
            return this.$route.params.event_number != null && this.$route.params.event_number > 0;
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
        async geteventAwards() {
            const { data, error } = await supabase.from("BlueBanner")
                .select()
                .eq("event_number", this.eventNumber)
                .eq("type", "event");
            var awardList = this.getAwardList(data);

            console.log(awardList)

            this.eventAwardsList = awardList
        },
        async getRobotAwards() {
            const { data, error } = await supabase.from("BlueBanner")
                .select()
                .eq("event_number", this.eventNumber)
                .eq("type", "Robot");
            var awardList = this.getAwardList(data);

            this.robotAwardsList = awardList
        },
        eventChange() {
            this.getRobotAwards();
            this.geteventAwards();
        }
    },
}
</script>

<style scoped>
div.event-header-container {
    display: flex;
    margin-bottom: 20px;
}

div.event-info-container {
    width: 50%;
    border: var(--bbq-primary-color) 2px solid;
    border-radius: 5px;
}

.event-container {
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

.event-text-field {
    align-items: center;
}

form.event-search-form {
    display: flex;
    height: 100px;
}
</style>