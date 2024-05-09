<script setup>
import { ref } from 'vue';
import { Chart as ChartJS, ArcElement, Tooltip, Legend, Colors, Title } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

import '@material/web/divider/divider'

ChartJS.register(ArcElement, Tooltip, Legend, Colors, Title)
</script>

<template>
    <div class="stat-row">
        <div v-for="stat in stats" class="stat-cell">
            <div class="stat-name">
                {{ stat.name }}
            </div>
            <div class="stat-value">
                {{ stat.value }}
            </div>
        </div>
    </div>
    <md-divider inset></md-divider>
    <div class="chart-view-container">
        <div class="chart-container">
            <Doughnut v-if="teamDataReady" :data="getRobotBbqContributions()" :options="robotBbqChartOptions">
            </Doughnut>
        </div>
        <div class="chart-container">
            <Doughnut v-if="teamDataReady" :data="getTeamAttributeBbqContributions()"
                :options="teamAttributeBbqChartOptions">
            </Doughnut>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            robotBbqChartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'left'
                    },
                    title: {
                        display: true,
                        text: "Robot Banners - Top 10 Teams"
                    }
                }
            },
            robotBbqBanners: {},
            // Making this a ref is very important for ensuring the data updates when it becomes available.
            robotBbqContributionData: ref({
                datasets: []
            }),
            teamAttributeBbqChartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'left'
                    },
                    title: {
                        display: true,
                        text: "Team Attribute Banners - Top 10 Teams"
                    }
                }
            },
            teamAttributeBbqBanners: {},
            // Making this a ref is very important for ensuring the data updates when it becomes available.
            teamAttributeBbqContributionData: ref({
                datasets: []
            })
        };
    },
    props: {
        stats: {
            default: []
        },
        teamData: {
            default: null
        }
    },
    computed: {
        teamDataReady() {
            return this.teamData !== null && Object.entries(this.teamData).length > 0;
        }
    },
    methods: {
        convertDictToSortedValueArray(dict) {
            var items = Object.keys(dict).map(function (key) {
                return [key, dict[key]];
            });

            // Sort the array based on the second element
            items.sort(function (first, second) {
                return second[1] - first[1];
            });

            return items;
        },
        getRobotBbqContributions() {
            // If the team data passed in is null, just return the default data.
            if (!this.teamData) {
                console.log("no team data!")
                return this.robotBbqContributionData;
            }

            this.robotBbqBanners = {};
            for (const [key, value] of Object.entries(this.teamData)) {
                this.robotBbqBanners[key] = value.robot_awards.length;
            }

            let robotBanners = this.convertDictToSortedValueArray(this.robotBbqBanners)
            let top10Bbq = robotBanners.slice(0, 10);

            let top10BbqBannersList = [];
            let top10BbqLabelsList = [];
            for (let i = 0; i < top10Bbq.length; i++) {
                top10BbqBannersList.push(top10Bbq[i][1]);
                top10BbqLabelsList.push(top10Bbq[i][0]);
            }

            let chartJsData = {
                labels: top10BbqLabelsList,
                datasets: [
                    {
                        label: "BBQ - Robot Banners",
                        data: top10BbqBannersList
                    }
                ]
            }
            this.robotBbqContributionData = chartJsData;

            return this.robotBbqContributionData;
        },
        getTeamAttributeBbqContributions() {
            // If the team data passed in is null, just return the default data.
            if (!this.teamData) {
                console.log("no team data!")
                return this.teamAttributeBbqContributionData;
            }

            this.teamAttributeBbqBanners = {};
            for (const [key, value] of Object.entries(this.teamData)) {
                this.teamAttributeBbqBanners[key] = value.team_awards.length;
            }

            let teamAttributeBanners = this.convertDictToSortedValueArray(this.teamAttributeBbqBanners)
            let top10Bbq = teamAttributeBanners.slice(0, 10);

            let top10BbqBannersList = [];
            let top10BbqLabelsList = [];
            for (let i = 0; i < top10Bbq.length; i++) {
                top10BbqBannersList.push(top10Bbq[i][1]);
                top10BbqLabelsList.push(top10Bbq[i][0]);
            }

            let chartJsData = {
                labels: top10BbqLabelsList,
                datasets: [
                    {
                        label: "BBQ - Team Attribute Banners",
                        data: top10BbqBannersList
                    }
                ]
            }
            this.teamAttributeBbqContributionData = chartJsData;

            return this.teamAttributeBbqContributionData;
        }
    }
}
</script>

<style scoped>
.stat-row {
    display: flex;
    flex-direction: row;
}

.chart-view-container {
    display: flex;
}

.chart-container {
    max-width: 30%;
    color: var(--bbq-primary-text-color);
}
</style>
