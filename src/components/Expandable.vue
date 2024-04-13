<script setup>

import '@material/web/icon/icon';

</script>

<template>
    <div class="expand-container">
        <div class="expand-title">
            <div>
                <slot name="title"></slot>
            </div>
            <a class="expand-link" v-if="enabled" @click="expanded = !expanded">
                <md-icon slot="icon" v-if="expanded">remove </md-icon>
                <md-icon slot="icon" v-if="!expanded">add</md-icon>
            </a>
        </div>
        <div class="expand-item">
            <slot v-if="expanded" name="content"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        enabled: {
            default: true,
            type: Boolean
        }
    },
    data() {
        return {
            expanded: false
        }
    }
}
</script>

<style scoped>
.expand-container {
    margin-bottom: 5px;
}

.expand-link {
    display: flex;
    align-items: center;
    justify-content: center;
}

.expand-link:hover {
    background-color: var(--bbq-secondary-foreground-component-link-hover-color);
    cursor: pointer;
}

.expand-title {
    display: flex;
    background-color: var(--bbq-foreground-component-color);
}

.expand-item {
    background-color: var(--bbq-secondary-foreground-component-color);
}
</style>
