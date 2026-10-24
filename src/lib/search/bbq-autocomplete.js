

import { supabase } from '@/lib/supabase-client';
import { buildSearchIndex } from '@/lib/search/search-index';

// The search index is shared by every search bar, so it is only loaded once per page load.
let searchIndexPromise = null;

export function getSearchIndex() {
    if (searchIndexPromise === null) {
        searchIndexPromise = fetchSearchIndex().catch((error) => {
            // Clear the failed request so the next search bar can retry.
            searchIndexPromise = null;
            throw error;
        });
    }

    return searchIndexPromise;
}

async function fetchSearchIndex() {
    // Prefer the static index generated at build time (see vite.config.js), which avoids supabase egress.
    try {
        const response = await fetch('/search-index.json');

        // In dev the file doesn't exist and the server responds with index.html instead.
        if (response.ok && response.headers.get('content-type')?.includes('application/json')) {
            return await response.json();
        }
    } catch (error) {
        console.log(error);
    }

    // Fall back to building the index from the database.
    return await buildSearchIndex(supabase);
}

export function initSearchDataStructure() {
    return {
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
                "items": [],
                "sort_direction": "descending"
            }
        ]
    }
}

export async function loadSearchData(online, searchData, searchCategories) {
    if (online) {
        let searchIndex;
        try {
            searchIndex = await getSearchIndex();
        } catch (error) {
            console.log(error);
            return;
        }

        loadTeams(searchIndex.teams, searchCategories.teams, searchData);
        loadEvents(searchIndex.events, searchCategories.events, searchData);
    } else {
        // Offline mode is for debugging only. This is especially useful when you 
        // are on an airplane and don't have WiFi, or if you want to carry out your 
        // own specific tests without the database connection.
        loadTeamsOffline(searchCategories.teams, searchData);
        loadEventsOffline(searchCategories.events, searchData);
    }
}

export function loadTeams(allTeams, categoryIndex, searchData) {
    for (let i = 0; i < allTeams.length; i++) {
        let teamItem = {
            "label": allTeams[i].team_number + " - " + allTeams[i].nickname,
            "route": "/team/" + allTeams[i].team_number
        }
        searchData.categories[categoryIndex].items.push(teamItem);
    }
}

export function loadEvents(allEvents, categoryIndex, searchData) {
    for (let i = 0; i < allEvents.length; i++) {
        let eventItem = {
            "label": allEvents[i].event_id + " - " + allEvents[i].year + " " + allEvents[i].name,
            "route": "/event/" + allEvents[i].event_id
        }
        searchData.categories[categoryIndex].items.push(eventItem);
    }
}

export function loadTeamsOffline(categoryIndex, searchData) {
    for (let i = 1; i < 9900; i++) {
        let teamItem = {
            "label": i + " - Team " + i,
            "route": "/team/" + i
        }
        searchData.categories[categoryIndex].items.push(teamItem);
    }
}

export function loadEventsOffline(categoryIndex, searchData) {
    searchData.categories[categoryIndex].items = [
        {
            "label": "2024 Falls Church",
            "route": "/event/2024vafal"
        }
    ]
}

export function autoCompleteRank(a, b, isAscendingTiebreaker) {
    let direction = isAscendingTiebreaker ? 1 : -1;

    // Sort by priority.
    if (a.priority < b.priority) {
        return -1;
    } else if (b.priority < a.priority) {
        return 1;
    }

    // If two items have equal priority, sort alphabetically 
    return a.item.label.localeCompare(b.item.label) * direction;
}

export function getCategoricalAutocompleteOptions(query, searchData) {
    let autoCompleteData = {
        "categories": []
    };

    // If there are no categories in searchData, return an empty list of options rather than searching for nothing.
    if (!("categories" in searchData)) {
        return autoCompleteData;
    }

    // TODO: this can definitely be optimized, however the search space is small enough that it hasn't been optimized. 
    for (let i = 0; i < searchData.categories.length; i++) {

        let options = [];
        let numOptions = 0;

        let searchCategory = searchData.categories[i];
        let categoryIndex = searchCategory.id;
        let categoryName = searchCategory.label;
        let searchItems = searchCategory.items;
        numOptions = searchCategory.autocomplete_max;

        const queryLength = query.length;

        // Tokenize the query into words in case that yields better results (in case the user types in a mostly correct search).
        const lowercaseQuery = query.toLocaleLowerCase();
        const queryTokens = lowercaseQuery.split(' ');

        // Loop through all items in this category and assign a priority ranking if they are applicable.
        for (let j = 0; j < searchItems.length; j++) {
            const lowercaseSearchItem = searchItems[j].label.toLocaleLowerCase();
            // The best options are those which start with the same characters as the query.
            if (lowercaseSearchItem.substring(0, queryLength).includes(lowercaseQuery)) {
                options.push({
                    "priority": 0,
                    "item": searchItems[j]
                });
            } else if (lowercaseSearchItem.includes(lowercaseQuery)) {
                options.push({
                    "priority": 1,
                    "item": searchItems[j]
                });
            } else {
                let tokenMatchCounter = 0;
                for (const k in queryTokens) {
                    let token = queryTokens[k];
                    if (token == "") {
                        continue;
                    }

                    if (lowercaseSearchItem.includes(token)) {
                        tokenMatchCounter += 1;
                    }
                }

                // If multiple words match, put this item in.
                if (tokenMatchCounter >= 2) {
                    options.push({
                        "priority": 4 - tokenMatchCounter,
                        "item": searchItems[j]
                    });
                }
            }

        }

        let isAscending = true;
        if ("sort_direction" in searchData.categories[i]) {
            isAscending = (searchData.categories[i].sort_direction == "ascending");
        }

        let sortedOptions = options.sort((a, b) => autoCompleteRank(a, b, isAscending));
        let filteredOptions = sortedOptions.slice(0, numOptions);

        autoCompleteData.categories[categoryIndex] = {
            "name": categoryName,
            "options": []
        }

        for (let j = 0; j < filteredOptions.length; j++) {
            autoCompleteData.categories[categoryIndex].options.push(filteredOptions[j].item);
        }
    }

    return autoCompleteData;
}
