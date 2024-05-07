

import { supabase } from '@/lib/supabase-client';

export async function loadSearchData(online, searchData, searchCategories) {
    if (online) {
        loadTeams(searchCategories.teams, searchData);
    } else {
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
    let allTeams = await this.loadTableData("Team", "*");

    for (let i = 0; i < allTeams.length; i++) {
        let teamItem = {
            "label": allTeams[i].team_number + " - " + allTeams[i].nickname,
            "route": "/team/" + allTeams[i].team_number
        }
        searchData.categories[categoryIndex].items.push(teamItem);
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