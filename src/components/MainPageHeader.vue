<script setup>
import { RouterLink } from 'vue-router';

</script>

<template>
    <div class="nav">
        <RouterLink to="/" class="nav"></RouterLink>

        <!--Right Aligned Elements-->
        <!-- <RouterLink to="/help" class="nav_txt">Help</RouterLink> -->


        <div id="search_bar">
            <span class="search">
                <!-- <input type="text" name="q" id="searcher" class="search" placeholder="Search" autocomplete="off"></input> -->
                <div id="results">
                </div>
            </span>
        </div>
    </div>
</template>

<script>
export default {}
</script>

<style scoped></style>