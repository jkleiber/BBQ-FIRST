<script setup>

import '@material/web/textfield/filled-text-field';
import '@material/web/button/filled-button';
import '@material/web/icon/icon';

</script>


<template>
    <span class="search-container">
        <form class="search-form">
            <input class="search-text-field nav-input" type="text" v-model="searchQuery"
                @keydown.enter.prevent="emitSubmit()" placeholder="Search teams or events"></input>
        </form>
        <div class="autocomplete-dropdown" v-if="searchQuery.length > 0 && searchableData()">
            <div v-for="category in searchData.categories">
                <div v-if="autoCompleteOptions(category).length > 0">
                    afaf
                </div>
            </div>
        </div>
    </span>
</template>

<script>
export default {
    // Search data is the data that can be looked up and takes the following form:
    /*
     {
        "categories": [
            {
                "label": "Teams",
                "autocomplete_max": 5,
                "items": [
                    {
                        "label": "Team 401",
                        "route": "/team/401"
                    },
                    ...
                ]
            },
            ...
        ]
     }
     */
    props: {
        label: {
            default: "",
            type: String
        },
        searchData: {
            default: {},
            type: Object
        }
    },
    data() {
        return {
            searchQuery: ""
        }
    },
    computed: {
        inputLabelText() {
            return this.label;
        },
        inputType() {
            return this.type;
        },
        searchableData() {
            let searchable = true;
            searchable &= "categories" in this.searchData;

            return searchable;
        },
        autoCompleteOptions(category) {
            let options = [];

            if ("categories" in this.searchData) {
                let searchCategory = this.searchData.categories[category];
                let searchItems = searchCategory.items;
                let num_options = searchCategory.autocomplete_max;

                options = searchItems.filter(({ label }) => label.toLowerCase().includes(this.searchQuery.toLowerCase()));
            }

            return options.slice(0, num_options);
        }
    },
    methods: {
        emitSubmit() {
            this.$emit('submit');
        }
    }
}
</script>


<style scoped>
span.search-container {
    display: flex;
}

form.search-form {
    display: flex;
    align-items: center;
}

label::before {
    content: attr(prefix)
}

.search-v-centered-button {
    top: 50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
    height: fit-content;
}

md-filled-text-field {
    resize: none;
    margin: 10px;
}

.search-text-field {
    align-items: center;
    margin: 0;
}
</style>