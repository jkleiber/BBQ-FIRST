<script setup>

import MainPageHeader from '@/components/MainPageHeader.vue';

import SearchBar from '@/components/SearchBar.vue';

import { loadSearchData } from '@/lib/search/bbq-autocomplete.js';
</script>

<template>
    <MainPageHeader :search-visible="true"></MainPageHeader>

    <div class="main-content">
        <div class="search-title-container">
            <img src="@/assets/bbqcode.gif" width="32" height="32" />
            <h1>BBQ Search</h1>
            <img src="@/assets/bbqcode.gif" width="32" height="32" />
        </div>
        <SearchBar :search-data="searchData" class="full-search"></SearchBar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchCategories: {
                "teams": 0,
                "events": 1
            },
            searchData: {
                "categories": [
                    {
                        "id": 0,
                        "label": "Teams",
                        "autocomplete_max": 5,
                        "items": []
                    },
                    {
                        "id": 1,
                        "label": "Events",
                        "autocomplete_max": 5,
                        "items": []
                    }
                ]
            }
        }
    },
    mounted() {
        loadSearchData(true, this.searchData, this.searchCategories);
    }
}
</script>

<style scoped>
.full-search {
    width: 100%;
}

.search-title-container {
    height: fit-content;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
