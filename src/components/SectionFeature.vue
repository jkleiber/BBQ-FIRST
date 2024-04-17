<script setup>

</script>

<template>
    <div class="section-feature" @click="pushRoute()">
        <h1>{{ name }}</h1>
        <p>
            {{ description }}
        </p>
    </div>
</template>

<script>
export default {
    props: ['name', 'description', 'page'],
    methods: {
        pushRoute() {
            if (this.page) {
                this.$router.push(this.page);
            }
        }
    }
}
</script>

<style scoped>
.section-feature {
    border-radius: 5px;
    border-color: var(--bbq-primary-color);
    border: 3px solid;
    cursor: pointer;
    width: 40%;
    height: 30%;
    margin: 25px;
    margin: 0 auto;
    align-items: center;
    justify-content: center;
    display: flex;
    flex-flow: column;
}

.section-feature:hover {
    background-color: var(--bbq-secondary-foreground-component-link-hover-color);
}
</style>
