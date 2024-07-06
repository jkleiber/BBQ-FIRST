<script setup>
</script>

<template>
    <footer class="footer">

        Powered by <a href="https://www.thebluealliance.com/">The Blue Alliance</a>
    </footer>
</template>

<script>
export default {
    props: {
    }
}
</script>

<style scoped>
.footer {
    background-color: var(--bbq-header-color);
    color: var(--bbq-primary-color);
    margin-top: auto;
}

a {
    color: var(--bbq-primary-color);
}
</style>