<script setup>

import '@jamescoyle/svg-icon';
import { mdiGithub } from '@mdi/js';

</script>

<template>
    <footer class="footer">
        <div>
            Powered by <a href="https://www.thebluealliance.com/">The Blue Alliance</a>
        </div>
        <div>
            <a href="https://github.com/jkleiber/BBQ-FIRST"><svg-icon type="mdi" :path="mdiGithub"
                    :size="32"></svg-icon></a>
        </div>
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