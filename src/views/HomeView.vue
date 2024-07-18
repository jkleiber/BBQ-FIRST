<script setup>
import Expandable from '@/components/Expandable.vue';

import '@material/web/button/outlined-button';

</script>

<template>
    <div class="home-section-container light-container">
        <div>
            <h1>Welcome to BBQ FIRST</h1>

            <div class="main-buttons">
                <RouterLink tag="button" to="/events" v-slot="{ navigate }" custom>
                    <md-outlined-button @click="navigate" role="link">Event Rankings</md-outlined-button>
                </RouterLink>
                <RouterLink tag="button" to="/teams" v-slot="{ navigate }" custom>
                    <md-outlined-button @click="navigate" role="link">Team Rankings</md-outlined-button>
                </RouterLink>
            </div>
        </div>
    </div>
    <div class="home-section-container accent-container">
        <div class="text-container">
            <h1>Stats</h1>

        </div>
        <div class="stat-container">
            <Expandable v-for="stat in stats">
                <template v-slot:title>
                    <p class="stat-title">{{ stat.shorthand }}</p>
                </template>
                <template v-slot:content>
                    <div class="stat-full-name">
                        {{ stat.fullName }}
                    </div>
                </template>
            </Expandable>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            'stats': [
                {
                    'shorthand': "BBQ",
                    'fullName': "Blue Banner Quotient",
                    'content': ""
                },
                {
                    'shorthand': "SAUCE",
                    'fullName': "Sextuple Advancement Uniform Counting Era",
                    'content': ""
                },
                {
                    'shorthand': "BRIQUETTE",
                    'fullName': "Banner Ratio Indexed to Quadrenniums of Unified Education Total Talent Estimation",
                    'content': ""
                }
            ]
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

.stat-container {
    width: 50%;
}

h1 {
    font-size: 48px;
}
</style>