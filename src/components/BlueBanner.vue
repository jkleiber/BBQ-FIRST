<script setup>
</script>

<template>
    <div class="bluebanner">
        <div class="title-container">
            <a v-bind:href="bannerLink" class="title-link"> {{ titleText }}</a>
        </div>
        <div class="banner-text-container">
            <div class="award-details-container">
                <div class="award-name-container">
                    <b>{{ awardNameText }}</b>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        // bannerInfo has the following keys:
        // Team information: teamNumber, teamName
        // Event information: eventId, eventName, year
        // Award information: awardName
        bannerInfo: {},
        mode: {}
    },
    data() {
        return {
        }
    },
    computed: {
        awardNameText() {
            return this.bannerInfo?.awardName;
        },
        titleText() {
            if (this.isEventMode()) {
                return this.bannerInfo?.teamNumber + " - " + this.bannerInfo?.teamName;
            }
            return this.bannerInfo?.year + " " + this.bannerInfo?.eventName;
        },
        bannerLink() {
            // In event mode, we are already at the event page, so link to the team who won the award.
            if (this.isEventMode()) {
                return "/team/" + this.bannerInfo?.teamNumber;
            }
            // Otherwise we are on the team page, so link to the event where the award was won.
            return "/event/" + this.bannerInfo?.eventId;
        },
    },
    methods: {
        isEventMode() {
            return this.mode && this.mode == 'event';
        },
    }
}
</script>

<style scoped>
.bluebanner {
    border-color: #0000FF;
    border-radius: 5px;
    border-style: solid;
    /* color: #FFF; */
    margin-bottom: 5px;
    /* height: fit-content; */
    min-height: 150px;
    width: 150px;
    background-color: #2790FF;
    margin: 10px;

    display: flex;
    flex-direction: column;
}

.title-container {
    color: #FFF;
}

a.title-link {
    color: #FFF;
}

.banner-text-container {
    text-align: center;
    justify-content: center;
    height: 100%;
    color: #FFF;
}

.award-details-container {
    position: relative;
    height: fit-content;
    top: 50%;
    transform: translateY(-50%);
}
</style>
