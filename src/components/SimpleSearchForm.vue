<script setup>

import '@material/web/textfield/outlined-text-field';
import '@material/web/button/filled-button';

</script>


<template>
    <form class="search-form">
        <md-outlined-text-field class="search-text-field" v-bind:type="inputType" v-bind:label="inputLabelText"
            v-model="searchVal" @keydown.enter="emitSubmit()"></md-outlined-text-field>
        <md-filled-button class="search-v-centered-button" type="submit"
            @click.stop.prevent="emitSubmit()">Submit</md-filled-button>
    </form>
</template>

<script>
export default {
    props: ['label', 'type', 'modelValue'],
    computed: {
        inputLabelText() {
            return this.label;
        },
        inputType() {
            return this.type;
        },
        searchVal: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit('update:modelValue', val);
            }
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
form.search-form {
    display: flex;
    height: 100px;
}

.search-v-centered-button {
    top: 50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
    height: fit-content;
}

md-outlined-text-field {
    resize: none;
    margin: 10px;
}

.search-text-field {
    align-items: center;
}
</style>