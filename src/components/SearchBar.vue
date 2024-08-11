<script setup>

import '@material/web/textfield/filled-text-field';
import '@material/web/button/filled-button';
import '@material/web/icon/icon';

import { getCategoricalAutocompleteOptions } from '@/lib/search/bbq-autocomplete';

</script>


<template>
    <span class="search-outer-container">
        <span class="search-container">
            <form v-bind:class="searchFormClass">
                <input class="search-text-field" type="text" v-model="searchQuery" @keydown.enter.prevent="search()"
                    v-bind:placeholder="placeholder" @keydown.up.prevent="decrementOption()"
                    @keydown.down.prevent="incrementOption()" @keyup="updateAutoComplete()" />
            </form>
            <div class="autocomplete-dropdown" v-if="searchQuery.length > 0 && searchableData()">
                <div v-for="category, categoryIndex in autoCompleteData.categories">
                    <div class="autocomplete-header">{{ category.name }}</div>
                    <div v-for="item, itemIndex in category.options">
                        <div v-if="itemIndex == selectedOption && categoryIndex == selectedCategory"
                            class="autocomplete-option-selected">
                            <RouterLink v-bind:to="item.route" class="autocomplete-link" @click="clearSearch()">{{
                                item.label }}</RouterLink>
                        </div>
                        <div v-else class="autocomplete-option">
                            <RouterLink v-bind:to="item.route" class="autocomplete-link" @click="clearSearch()">{{
                                item.label }}</RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </span>
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
        },
        mobile: {
            default: false,
            type: Boolean
        }
    },
    data() {
        return {
            searchQuery: "",
            lastQuery: null,
            selectedOption: -1,
            selectedCategory: -1,
            autoCompleteData: {
                "categories": []
            }
        }
    },
    computed: {
        inputLabelText() {
            return this.label;
        },
        inputType() {
            return this.type;
        },
        searchFormClass() {
            let formClass = "search-form";
            if (this.mobile == true) {
                formClass = "mobile-search-form";
            }
            return formClass;
        }
    },
    methods: {
        clearSearch() {
            // Clear the query text for a new search.
            this.searchQuery = "";
        },
        search() {
            // If the user commands a search with no option selected, do nothing.
            if (this.selectedOption < 0 || this.selectedCategory < 0) {
                return;
            }

            // Clear the query text for a new search.
            this.searchQuery = "";

            // Otherwise, go to the page requested by the user.
            this.$router.push(this.autoCompleteData.categories[this.selectedCategory].options[this.selectedOption].route);
        },
        incrementOption() {
            // Return early if an invalid pre-condition occurs.
            if (!("categories" in this.autoCompleteData) || this.autoCompleteData.categories.length == 0) {
                this.selectedCategory = -1;
                this.selectedOption = -1;
                return;
            }

            // If this is the first time an option is being selected, set the indices to start highlighting things
            if (this.selectedCategory < 0) {
                this.selectedCategory = 0;
            }

            // Increment the option index.
            this.selectedOption += 1;

            // If incrementing puts us beyond the end of this category's list, move to the next category that has non-zero length.
            if (this.selectedOption >= this.autoCompleteData.categories[this.selectedCategory].options.length) {
                // Set the category to beyond the end of the list so that the wrapping case can catch it.
                let nextCategory = this.selectedCategory + 1;
                this.selectedCategory = this.autoCompleteData.categories.length;
                for (let i = nextCategory; i < this.autoCompleteData.categories.length; i++) {
                    if (this.autoCompleteData.categories[i].options.length > 0) {
                        this.selectedCategory = i;
                        break;
                    }
                }

                this.selectedOption = 0;
            }

            // If the selected category is outside the number of categories, wrap to the top of the first category with results.
            if (this.selectedCategory >= this.autoCompleteData.categories.length) {
                for (let i = 0; i < this.autoCompleteData.categories.length; i++) {
                    if (this.autoCompleteData.categories[i].options.length > 0) {
                        this.selectedCategory = i;
                        break;
                    }
                }
                this.selectedOption = 0;
            }

            // If the selected category is still out of range, then there are no selectable options.
            if (this.selectedCategory >= this.autoCompleteData.categories.length) {
                this.selectedCategory = -1;
                this.selectedOption = -1;
            }
        },
        decrementOption() {
            // Return early if an invalid pre-condition occurs.
            if (!("categories" in this.autoCompleteData) || this.autoCompleteData.categories.length == 0) {
                this.selectedCategory = -1;
                this.selectedOption = -1;
                return;
            }

            // If this is the first time an option is being selected, set the indices to 
            // the end of the option list to start highlighting things from the last 
            // category with data available.
            if (this.selectedCategory < 0) {
                for (let i = this.autoCompleteData.categories.length - 1; i >= 0; i--) {
                    if (this.autoCompleteData.categories[i].options.length > 0) {
                        this.selectedCategory = i;
                        break;
                    }
                }

                // If this is still negative, that means there are no selectable options.
                if (this.selectedCategory < 0) {
                    this.selectedOption = -1;
                    return;
                }

                this.selectedOption = this.autoCompleteData.categories[this.selectedCategory].options.length;
            }

            // Decrement the option index.
            this.selectedOption -= 1;

            // If the option index is now less than 0, move to a previous category with 
            // selectable options and start from the end of that category.
            if (this.selectedOption < 0) {
                let prevCategory = this.selectedCategory - 1;
                this.selectedCategory = -1;
                for (let i = prevCategory; i >= 0; i--) {
                    if (this.autoCompleteData.categories[i].options.length > 0) {
                        this.selectedCategory = i;
                        break;
                    }
                }

                // If the selected category is now outside the number of categories, 
                // wrap to the bottom and select a category with options.
                if (this.selectedCategory < 0) {
                    for (let i = this.autoCompleteData.categories.length - 1; i >= 0; i--) {
                        if (this.autoCompleteData.categories[i].options.length > 0) {
                            this.selectedCategory = i;
                            break;
                        }
                    }

                    // If selected category is still negative, that means there are no selectable options.
                    if (this.selectedCategory < 0) {
                        this.selectedOption = -1;
                        return;
                    }
                }

                this.selectedOption = this.autoCompleteData.categories[this.selectedCategory].options.length - 1;
            }
        },
        searchableData() {
            let searchable = true;
            searchable &= "categories" in this.searchData;

            return searchable;
        },
        updateAutoComplete() {
            // NOTE: this function should be called on keyup in order to 
            // capture the latest frame of query data.

            if ("categories" in this.searchData) {
                this.autoCompleteData = getCategoricalAutocompleteOptions(this.searchQuery, this.searchData);

                // If there is no query, make the indices invalid.
                if (this.searchQuery.length == 0) {
                    this.selectedCategory = -1;
                    this.selectedOption = -1;
                }
                else if (!this.lastQuery || this.searchQuery != this.lastQuery) {
                    // Default to highlighting the first option in the first non-blank category.
                    for (let c = 0; c < this.autoCompleteData.categories.length; c++) {
                        if (this.autoCompleteData.categories[c].options.length > 0) {
                            this.selectedCategory = c;
                            this.selectedOption = 0;
                            break;
                        }
                    }
                }
                this.lastQuery = this.searchQuery;

            } else {
                // there are no categories in searchData, so make the indices invalid.
                this.selectedCategory = -1;
                this.selectedOption = -1;
            }
        }
    }
}
</script>


<style scoped>
.search-outer-container {
    align-content: center;
}

.search-container {
    display: block;
    width: 100%;
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
    width: 100%;
}

/* Set links to inherit color so they can be set by the autocomplete option classes */
a {
    color: inherit;
}

a.autocomplete-link {
    width: 100%;
    text-decoration: none;
    cursor: pointer;
    display: block;
}

.autocomplete-header {
    font-weight: bold;
    color: var(--bbq-nav-component-text-color);
    background-color: var(--bbq-autocomplete-default-color);
}

.autocomplete-option {
    color: var(--bbq-nav-component-text-color);
    background-color: var(--bbq-autocomplete-default-color);
}

.autocomplete-option:hover,
.autocomplete-option-selected {
    color: var(--bbq-nav-component-text-color);
    background-color: var(--bbq-autocomplete-hover-color);
}

form.mobile-search-form {
    display: flex;
    align-items: center;
    margin: 5px;
}
</style>