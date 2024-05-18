<script setup>
import { RouterLink } from 'vue-router';

import SearchBar from '@/components/SearchBar.vue';
import HamburgerMenu from '@/components/HamburgerMenu.vue';

import { loadSearchData, initSearchDataStructure } from '@/lib/search/load_autocomplete.js';
import { viewModeStore } from '@/stores/view-mode-store';
</script>

<template>
    <div class="nav">
        <RouterLink to="/" class="nav-home"></RouterLink>

        <!--Right Aligned Elements-->
        <!-- <RouterLink to="/help" class="nav-link">Help</RouterLink> -->

        <!-- <RouterLink to="/teams" class="nav-link">Teams</RouterLink> -->
        <!-- <RouterLink to="/events" class="nav-link">Events</RouterLink> -->

        <div v-if="viewMode?.isMobile">
            <HamburgerMenu>
                <template v-slot:menu-content>
                    <span v-if="searchVisible">
                        <SearchBar :search-data="searchData" :mobile="true"></SearchBar>
                    </span>
                </template>
            </HamburgerMenu>
        </div>
        <div v-else>
            <span class="nav-search" v-if="searchVisible">
                <SearchBar :search-data="searchData" :mobile="false"></SearchBar>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        searchVisible: {
            default: true,
            type: Boolean
        }
    },
    data() {
        return {
            searchCategories: {
                "teams": 0,
                "events": 1
            },
            searchData: initSearchDataStructure(),
            windowWidth: window.innerWidth,
            viewMode: null
        }
    },
    mounted() {
        this.viewMode = viewModeStore();

        loadSearchData(true, this.searchData, this.searchCategories);
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
    display: block;
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
    float: right;
    position: relative;
    margin-right: 20px;
    /* This is a total hack to get the search bar to 
    stay in a fixed position when text is entered into it. */
    height: 50px;
    margin-top: 7px;
    width: 200px;
}
</style>