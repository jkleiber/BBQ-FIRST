<script setup>

import '@material/web/textfield/filled-text-field';
import '@material/web/button/filled-button';
import '@material/web/icon/icon';

</script>


<template>
    <span class="search-container">
        <form class="search-form">
            <input class="search-text-field nav-input" type="text" v-model="searchQuery"
                @keydown.enter.prevent="emitSubmit()" v-bind:placeholder="placeholder"></input>
        </form>
        <div class="autocomplete-dropdown" v-if="searchQuery.length > 0 && searchableData()">
            <div v-for="category in searchData.categories">
                <div v-for="item in autoCompleteOptions(category.id)">
                    {{ item.label }}
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
                "id": 0,
                "label": "Teams",
                "autocomplete_max": 5,
                "items": [
                    {
                        "label": "401 - Copperhead Robotics",
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
        },
        placeholder: {
            default: "Search teams or events",
            type: String
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
        }
    },
    methods: {
        emitSubmit() {
            this.$emit('submit');
        },
        searchableData() {
            let searchable = true;
            searchable &= "categories" in this.searchData;

            return searchable;
        },
        autoCompleteOptions(category) {
            let options = [];
            let numOptions = 0;

            if ("categories" in this.searchData) {
                let searchCategory = this.searchData.categories[category];
                let searchItems = searchCategory.items;
                numOptions = searchCategory.autocomplete_max;

                options = searchItems.filter(({ label }) => label.toLowerCase().includes(this.searchQuery.toLowerCase()));
            }

            console.log(category);
            console.log(options.slice(0, numOptions));

            return options.slice(0, numOptions);
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