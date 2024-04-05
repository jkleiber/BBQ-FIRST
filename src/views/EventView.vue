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
            <div class="event-info-container" v-if="doesEventExist()">
                <h1 class="event-number-header"> {{ eventTitleText }} </h1>
            </div>

            <form class="event-search-form">
                <md-outlined-text-field class="event-text-field" type="text" label="Event Name/Code"
                    v-model="eventCodeModel" @keydown.enter="submit()"></md-outlined-text-field>
                <md-filled-button class="v-centered-button" type="submit"
                    @click.stop.prevent="submit()">Submit</md-filled-button>
            </form>
        </div>


        <!-- Show event information if it exists -->
        <div class="event-container" v-if="doesEventExist()">
            <md-list>
                <AwardItem v-for="award in robotAwardsList" :name="award.name" :season="award.season" :event="award.event">
                </AwardItem>
            </md-list>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            eventCodeModel: this.$route.params.event_code,
            eventCode: this.$route.params.event_code,
            eventString: "",
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
            if (this.$route.params.event_code != null) {
                this.eventString = "Event " + this.$route.params.event_code;
            }

            return this.eventString;
        },
    },
    methods: {
        submit() {
            // Process the event number into a string for the URL and route.
            this.eventCode = this.eventCodeModel;
            if (this.eventCode == null) {
                this.eventCode = "";
            }

            // Update the URL to match the event number.
            history.pushState(
                {},
                null,
                "/event/" + this.eventCode
            );

            // Go to the event page.
            this.$router.push("/event/" + this.eventCode);

            // Update the event information.
            this.eventChange();
        },
        changeTab(tab) {
            this.activeTab = tab.id;
        },
        isActive(id) {
            return id == this.activeTab;
        },
        doesEventExist() {
            if (this.eventCode == null || this.eventCode == undefined || this.eventCode == "") {
                return false;
            }

            return true;
        },
        async getEventInfo() {
            // const { data, error } = await supabase.from("Event")
            //     .select()
            //     .eq("event_code", this.eventNumber)
            //     .eq("type", "event");
            // var awardList = this.getAwardList(data);

            // console.log(awardList)

            // this.eventAwardsList = awardList
        },
        eventChange() {
            if (this.doesEventExist()) {
                this.getEventInfo();
            }
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
    overflow: auto;
}

.event-text-field {
    align-items: center;
}

form.event-search-form {
    display: flex;
    height: 100px;
}
</style>