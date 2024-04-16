<script setup>

import { supabase } from '@/lib/supabase-client';

import MainPageHeader from '@/components/MainPageHeader.vue';
import TableHeader from '@/components/TableHeader.vue';
import EventDataRow from '@/components/EventDataRow.vue';

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
        <!-- Filters -->

        <!-- Rankings -->
        <div v-if="!loadingEvents && eventList.length > 0">
            <table>
                <TableHeader :columns="activeColumns"></TableHeader>
                <EventDataRow v-for="event in eventList" :event-id="event.event_id" :name="event.name" :year="event.year"
                    :event-data="event.event_data"></EventDataRow>
            </table>
        </div>
        <div v-else-if="loadingEvents">
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
            activeColumns: ["Rank", "Event Name", "Robot BBQ", "Team Attribute BBQ"]
        }
    },
    created() {
        this.rankEvents();
    },
    methods: {
        async getEvents() {
            this.loadingEvents = true;
            const { data, error } = await supabase.from("EventData")
                .select("event_id, robot_bbq, team_bbq, Event( event_id, name, year, type, type_string)")
                .order("robot_bbq", { ascending: false })
                .limit(100);

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
                    this.eventList.push(eventInfo);
                }

                console.log(this.eventList)
            }

            // Mark the event as loaded to allow the doesEventExist function to determine existence of a loaded event.
            this.loadingEvents = false;
        },
        async rankEvents() {
            await this.getEvents();
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