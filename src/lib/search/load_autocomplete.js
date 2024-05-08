

import { supabase } from '@/lib/supabase-client';

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
        await loadTeams(searchCategories.teams, searchData);
        await loadEvents(searchCategories.events, searchData);
    } else {
        // Offline mode is for debugging only. This is especially useful when you 
        // are on an airplane and don't have WiFi, or if you want to carry out your 
        // own specific tests without the database connection.
        loadTeamsOffline(searchCategories.teams, searchData);
        loadEventsOffline(searchCategories.events, searchData);
    }
}

export async function loadTableData(table, columnString) {
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
}

export async function loadTeams(categoryIndex, searchData) {
    let allTeams = await loadTableData("Team", "*");

    for (let i = 0; i < allTeams.length; i++) {
        let teamItem = {
            "label": allTeams[i].team_number + " - " + allTeams[i].nickname,
            "route": "/team/" + allTeams[i].team_number
        }
        searchData.categories[categoryIndex].items.push(teamItem);
    }
}

export async function loadEvents(categoryIndex, searchData) {
    let allEvents = await loadTableData("Event", "*");

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