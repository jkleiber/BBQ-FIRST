<script setup>
import MainPageHeader from '@/components/MainPageHeader.vue';

import '@/assets/bbq.css';
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
    </div>
</template>

<script>

export default {
    data() {
        return {
            teamNumber: this.$route.params.team_number,
            teamString: null
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
        }
    },
}
</script>