<script setup>

import { supabase } from '@/lib/supabase-client';

import MainPageHeader from '@/components/MainPageHeader.vue';
import TeamItem from '@/components/TeamItem.vue';
import SearchForm from '@/components/SearchForm.vue';
import StatSummary from '@/components/StatSummary.vue';

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
                <h2 class="event-number-header"> {{ eventTitleText }} </h2>
                <h3> {{ eventTimeText }}</h3>
            </div>

            <SearchForm label="Event Code/Name" type="text" v-model="eventCodeModel" @submit="submit()">
            </SearchForm>
        </div>

        <md-list>

        </md-list>
        <!-- Show event information if it exists -->
        <div class="event-container" v-if="doesEventExist()">
            <StatSummary :stats="eventStats"></StatSummary>
            <md-list>
                <TeamItem v-for="team in teamDict" :number="team.number" :name="team.name" :country="team.country"
                    :robot-awards="team.robot_awards" :team-awards="team.team_awards">
                </TeamItem>
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
            eventName: "",
            eventYear: 0,
            eventSeasonWeek: -1,
            eventDate: null,
            loadingEvent: false,
            eventExists: false,
            teamDict: {},
            eventData: [],
            trip: {
                name: '',
                location: null,
                start: null,
                end: null,
            },
            locations: ['Australia', 'Barbados', 'Chile', 'Denmark', 'Ecuador', 'France'],
        }
    },
    created() {
        this.eventChange();
    },
    computed: {
        eventTitleText() {
            this.eventString = "";
            if (this.$route.params.event_code != null) {
                this.eventString = this.eventName;
            }

            return this.eventString;
        },
        eventTimeText() {
            let eventTimeString = "";
            eventTimeString += this.eventYear;

            if (this.eventSeasonWeek !== null && this.eventSeasonWeek !== undefined) {
                eventTimeString += " - Week " + this.eventSeasonWeek;
            }

            return eventTimeString;
        },
        eventStats() {
            return this.eventData;
        }
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
            // If there is no user input, the event doesn't exist.
            if (this.eventCode == null || this.eventCode == undefined || this.eventCode == "") {
                return false;
            }

            // If the event does not exist in the database and the database call has completed, it doesn't exist.
            if (!this.loadingEvent && !this.eventExists) {
                return false;
            } else if (this.loadingEvent &&
                (this.eventName == ""
                    || this.eventName == null
                    || this.eventName == undefined
                    || this.eventYear == 0
                    || this.eventSeasonWeek == -1)) {
                // If there is missing stale event data during a load, then don't change the view until data is refreshed.
                return false;
            }

            return true;
        },
        async getEventInfo() {
            this.loadingEvent = true;
            const { data, error } = await supabase.from("Event").select().eq("event_id", this.eventCode);

            if (error) {
                this.resetEventInfo();
                console.log(error);
            } else if (data.length == 0) {
                this.resetEventInfo();
            } else {
                this.eventExists = true;

                // Populate the data based on the first event pulled.
                this.eventName = data[0].name;
                this.eventYear = data[0].year;
                this.eventSeasonWeek = data[0].week;
                this.eventDate = data[0].start_date;
            }

            // Mark the event as loaded to allow the doesEventExist function to determine existence of a loaded event.
            this.loadingEvent = false;
        },
        async getEventData() {
            const { data, error } = await supabase.from("EventData").select().eq("event_id", this.eventCode);

            if (error) {
                console.log(error);
                this.eventData = [];
            } else {
                this.eventData = [
                    {
                        "name": "Robot BBQ",
                        "value": data[0].robot_bbq
                    },
                    {
                        "name": "Team Attribute BBQ",
                        "value": data[0].team_bbq
                    },
                ]
            }
        },
        async populateAwards() {
            console.log("AWARD POP " + this.teamDict.length)
            // Get all awards earned by teams prior to the event.
            const { data, error } = await supabase.from("BlueBanner")
                .select("team_number, type, date, event_id, name, Event (event_id, name, year)")
                .in("team_number", Object.keys(this.teamDict))
                .lt("date", this.eventDate);

            if (error) {
                console.log(error);
                return;
            }

            // Go through each award and assign it to the appropriate team.
            for (var i = 0; i < data.length; i++) {
                const team_number = data[i].team_number;

                let award_name = data[i].name;
                let event_name = data[i].Event.name;
                let event_year = data[i].Event.year;

                console.log(award_name)

                // Add the awards to the applicable teams based on their type.
                if (data[i].type == "Robot") {
                    this.teamDict[team_number].robot_awards.push({
                        "award_name": award_name,
                        "event_name": event_name,
                        "event_year": event_year
                    });
                } else if (data[i].type == "Team") {
                    this.teamDict[team_number].team_awards.push({
                        "award_name": award_name,
                        "event_name": event_name,
                        "event_year": event_year
                    });
                }
            }

            console.log(this.teamDict);
        },
        async getEventTeams() {
            const { data, error } = await supabase.from("Appearance")
                .select('event_id, team_number, Team (team_number, nickname, country)')
                .eq("event_id", this.eventCode)
                .order('team_number', { ascending: true });

            this.teamDict = {};
            if (error) {
                console.log(error);
                return;
            } else {
                for (var i = 0; i < data.length; i++) {
                    let team_number = data[i].team_number;
                    this.teamDict[team_number] = {
                        "number": team_number,
                        "name": data[i].Team.nickname,
                        "country": data[i].Team.country,
                        "robot_awards": [],
                        "team_awards": []
                    };
                }
            }

            await this.populateAwards();
        },
        resetEventInfo() {
            this.eventExists = false;
            this.eventName = "";
            this.eventSeasonWeek = -1;
            this.eventYear = 0;
            this.eventDate = null;
        },
        async eventChange() {
            await this.getEventInfo();

            if (this.eventExists) {
                this.getEventData();
                this.getEventTeams();
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
</style>