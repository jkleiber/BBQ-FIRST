<script setup>

import '@material/web/button/outlined-button';
import '@material/web/divider/divider';
import '@jamescoyle/svg-icon';
import { mdiChevronDown } from '@mdi/js';
</script>

<template>
    <div class="home-section-container light-container">
        <div>
            <h1>Welcome to BBQ FIRST</h1>

            <div class="main-buttons">
                <RouterLink tag="button" to="/events" v-slot="{ navigate }" custom>
                    <md-outlined-button @click="navigate" role="link" class="main-button">Event
                        Rankings</md-outlined-button>
                </RouterLink>

                <RouterLink tag="button" to="/teams" v-slot="{ navigate }" custom>
                    <md-outlined-button @click="navigate" role="link" class="main-button">Team
                        Rankings</md-outlined-button>
                </RouterLink>
            </div>
        </div>
        <div class="bottom-button-container">
            <a href="#stats" class="home-transition-button">
                <svg-icon type="mdi" :path="mdiChevronDown"></svg-icon>
            </a>
        </div>
    </div>
    <div class="home-section-container accent-container" style="min-height: fit-content;">
        <a class="anchor" id="stats"></a>
        <h1 class="full-header">Stats</h1>

        <div class="stat-container">
            <h2 class="stat-title">BBQ</h2>
            <div class="stat-full-name">
                <p><strong>Blue Banner Quotient</strong></p>
            </div>
            <div>
                For teams, BBQ is the average number of blue banners won per year since the team was founded. Team BBQ
                represents how dominant a team has been over their entire history.
                <br>
                For events, BBQ is the average number of blue banners won per team attending the event. Event
                BBQ represents how competitive the event is based on historical team success.
            </div>
        </div>

        <div class="stat-container">
            <h2 class="stat-title">SAUCE</h2>

            <div class="stat-full-name">
                <p><strong>Sextuple Advancement Uniform Counting Era</strong></p>
            </div>
            <div>
                In 2005, FIRST introduced the 3v3 format for alliances. At BBQ FIRST, we consider this the
                beginning
                of the modern era of FRC.<br>
                For teams, SAUCE is the average number of blue banners won per year since 2005. Team SAUCE
                represents how dominant a team has been in the modern era of FRC.<br>
                For events, SAUCE is the average number of blue banners won since 2005 per attending team. Event
                SAUCE represents how competitive the event is based on team success in the modern era.
            </div>
        </div>

        <div class="stat-container">
            <h2 class="stat-title">BRIQUETTE</h2>
            <div class="stat-full-name">
                <p><strong>Banner Ratio Indexed to Quadrenniums of Unified Education Total Talent
                        Estimation</strong></p>
            </div>
            <div>
                FRC teams have an entirely new student roster every 4 years. BRIQUETTE takes that into account
                using
                a rolling 4 year window of team success.
                <br>
                For teams, BRIQUETTE is the number of banners they have won per year, over the past 4 seasons. A
                team's BRIQUETTE represents how strong a team has been over the current senior class's tenure.
                <br>
                For events, BRIQUETTE is the average number of banners per year per attending team won over the
                last
                4 years. An event's BRIQUETTE represents how competitive the event is based on recent success of
                attending teams.<br>
                During a season, this stat is computed as a weighted average of banners won in the current
                season and season 4 seasons ago (based on linear interpolation of the current season's
                progress).<br>
            </div>
        </div>

        <div class="stat-container">
            <h2 class="stat-title">RIBS</h2>
            <div class="stat-full-name">
                <p><strong>Recent Index of Banners</strong></p>
            </div>
            <div>
                For teams, RIBS represents the number of banners won over the past year of competition.
                A team's RIBS indicates how strong the team was over the last year (and how much momentum they
                may
                be carrying into the next season). At the end of the season, this is always a whole number.
                <br>
                For events, RIBS represents the number of banners the average team attending the event has won
                over the past year of competition. An event's RIBS indicates how strong the event is based on
                the
                previous season's results.
                <br>
                During a season, this stat is computed as a weighted average of banners won in the current
                season and the previous season (based on linear interpolation of the current season's
                progress).<br>
            </div>

        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
        }
    },
    computed: {
    },
    methods: {
    },
}
</script>

<style scoped>
.home-section-container {
    display: flex;
    margin: 0 auto;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    min-height: 100%;
    min-width: 100%;
    flex-direction: column;
}

.main-button {
    margin: 5px;
}

a.anchor {
    display: block;
    position: relative;
    top: -65px;
    visibility: hidden;
}

a.home-transition-button {
    width: 64px;
    height: 64px;
    background-color: var(--bbq-primary-color-soft);
    color: #fff;
    align-items: center;
    justify-content: center;
    display: flex;
    border-radius: 32px;
    line-height: 1.0;
}

a.home-transition-button:hover {
    background-color: var(--bbq-primary-color);
}

.bottom-button-container {
    position: absolute;
    left: 50%;
    bottom: 20px;
    transform: translate(-50%, -50%);
    margin: 0 auto;
}

.dark-container {
    color: #fff;
    background-color: black;
}

.light-container {
    color: var(--bbq-primary-text-color);
    background-color: var(--bbq-background-color);
}

.accent-container {
    background-color: var(--bbq-primary-color-soft);
}

.full-header {
    width: 100%;
}

.stat-container {
    border: #000 3px solid;
    padding: 10px;
    border-radius: 5px;
    margin: 5px;
    width: 80%;
}

h1 {
    font-size: 48px;
}
</style>