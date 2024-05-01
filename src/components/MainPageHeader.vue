<script setup>
import { RouterLink } from 'vue-router';

import SearchBar from '@/components/SearchBar.vue';
import { supabase } from '@/lib/supabase-client';
</script>

<template>
    <div class="nav">
        <RouterLink to="/" class="nav-home"></RouterLink>

        <!--Right Aligned Elements-->
        <!-- <RouterLink to="/help" class="nav-link">Help</RouterLink> -->

        <RouterLink to="/team" class="nav-link">Teams</RouterLink>
        <RouterLink to="/event" class="nav-link">Events</RouterLink>


        <SearchBar class="nav-search" :search-data="searchData"></SearchBar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchCategories: {
                "teams": 0
            },
            searchData: {
                "categories": [
                    {
                        "id": 0,
                        "label": "Teams",
                        "autocomplete_max": 5,
                        "items": []
                    }
                ]
            }
        }
    },
    mounted() {
        this.loadSearchData()
    },
    methods: {
        async loadSearchData() {
            await this.loadTeams()
        },
        async loadTableData(table, columnString) {
            // Get the number of teams in the database.
            const { data, count } = await supabase.from(table).select(columnString, { count: 'exact', head: true });

            // Supabase paginates requests at 1000 rows, so we will make multiple calls in order to collect all the team data.
            const rowsPerRequest = 1000;
            let numTotalRows = count;
            let numPages = Math.ceil(numTotalRows / rowsPerRequest);
            let queryResult = [];

            for (let i = 0; i < numPages; i++) {
                let startIndex = i * rowsPerRequest;
                const { data, error } = await supabase.from(table).select(columnString).range(startIndex, startIndex + rowsPerRequest);

                if (error) {
                    console.log(error);
                    break;
                }

                for (let j = 0; j < data.length; j++) {
                    queryResult.push(data[j]);
                }
            }

            return queryResult;
        },
        async loadTeams() {
            let allTeams = await this.loadTableData("Team", "*");

            let categoryIndex = this.searchCategories.teams;

            for (let i = 0; i < allTeams.length; i++) {
                let teamItem = {
                    "label": allTeams[i].team_number + " - " + allTeams[i].nickname,
                    "route": "team/" + allTeams[i].team_number
                }
                this.searchData.categories[categoryIndex].items.push(teamItem);
            }

            console.log(this.searchData)
        }
    }
}
</script>

<style scoped>
div.nav {
    background-color: var(--bbq-header-color);
    color: var(--bbq-primary-text-color);
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    width: 100%;
    height: 65px;
    position: fixed;
}

a.nav-home {
    /* Image */
    background-image: url("/assets/navicon.png");

    /* Positioning */
    margin: 0 auto;
    float: left;
    height: 65px;
    width: 265px;
}

a.nav-link {
    /* Color transitions */
    transition: color .5s ease;
    transition: background-color .5s ease;
    -ms-transition: color .5s ease;
    -ms-transition: background-color .5s ease;
    -moz-transition: color .5s ease;
    -moz-transition: background-color .5s ease;
    -webkit-transition: color .5s ease;
    -webkit-transition: background-color .5s ease;

    /* Border transitions */
    /* -webkit-transition: border .5s ease;
    -moz-transition: border .5s ease;
    transition: border .5s ease; */

    /* Positioning */
    position: relative;
    margin: 0 auto;
    float: left;
    display: flex;
    justify-content: center;
    align-items: center;

    /* Sizing */
    font-size: 16px;
    height: 100%;
    text-align: center;
    margin: 0 auto;
    padding-left: 10px;
    padding-right: 10px;

    /* Text */
    text-decoration: none;
}


a.nav-link:link,
a.nav-link:visited {
    background-color: var(--bbq-header-color);
    color: #FFF;
    border-bottom: var(--bbq-header-color) 5px solid;
}

a.nav-link:hover,
a.nav-link:active {
    background-color: var(--bbq-header-hover-color);
    color: #FFF;
    border-bottom: var(--bbq-primary-color) 5px solid;
}

.nav-search {
    height: 100%;
    float: right;
    position: relative;
    margin-right: 20px;
}
</style>