<script setup>
import Expandable from '@/components/Expandable.vue';

</script>

<template>
    <Expandable :enabled="hasAwards">
        <template v-slot:title>
            <a v-bind:href="teamLink" class="team-title">{{ expandTitleText }}</a>
        </template>
        <template v-slot:content>
            <div class="award-view">
                <div class="award-container" v-if="robotAwards.length > 0">
                    <u>
                        <h3>Robot Awards</h3>
                    </u>
                    <div v-for="award in robotAwards">
                        {{ award.award_name }} @ <a v-bind:href="award.event_id">{{ award.event_year }} {{ award.event_name
                        }}</a>
                    </div>
                </div>
                <div class="award-container" v-if="teamAwards.length > 0">
                    <u>
                        <h3>Team Attribute Awards</h3>
                    </u>
                    <div v-for="award in teamAwards">
                        {{ award.award_name }} @ <a v-bind:href="award.event_id">{{ award.event_year }} {{ award.event_name
                        }}</a>
                    </div>
                </div>
            </div>
        </template>
    </Expandable>
</template>

<script>
export default {
    props: ['number', 'name', 'country', 'robot-awards', 'team-awards'],
    computed: {
        expandTitleText() {
            return this.number + " - " + this.name + " - " + this.country;
        },
        hasAwards() {
            return (this.robotAwards.length > 0) || (this.teamAwards.length > 0);
        },
        teamLink() {
            return "/team/" + this.number;
        }
    }
}
</script>

<style scoped>
.team-container {
    margin-bottom: 5px;
}

.team-title {
    font-size: 18px;
}

.award-view {
    display: flex;
    flex-wrap: wrap;
}

.award-container {
    max-width: 700px;
}
</style>
