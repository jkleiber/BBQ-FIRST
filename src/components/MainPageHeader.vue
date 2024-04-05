<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
    <div class="nav">
        <RouterLink to="/" class="nav-home"></RouterLink>

        <!--Right Aligned Elements-->
        <!-- <RouterLink to="/help" class="nav-link">Help</RouterLink> -->

        <RouterLink to="/team" class="nav-link">Teams</RouterLink>
        <RouterLink to="/event" class="nav-link">Events</RouterLink>


        <!-- <div id="search_bar">
            <span class="search">
                <input type="text" name="q" id="searcher" class="search" placeholder="Search" autocomplete="off"></input>
                <div id="results">
                </div>
            </span>
        </div> -->
    </div>
</template>

<script>
export default {}
</script>

<style scoped>
div.nav {
    background-color: var(--bbq-header-color);
    color: var(--bbq-primary-text-color);
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    width: 100%;
    height: 65px;
    position: fixed;
}

a.nav-home {
    /* Image */
    background-image: url("/assets/navicon.png");

    /* Positioning */
    margin: 0 auto;
    float: left;
    height: 65px;
    width: 265px;
}

a.nav-link {
    /* Color transitions */
    transition: color .5s ease;
    transition: background-color .5s ease;
    -ms-transition: color .5s ease;
    -ms-transition: background-color .5s ease;
    -moz-transition: color .5s ease;
    -moz-transition: background-color .5s ease;
    -webkit-transition: color .5s ease;
    -webkit-transition: background-color .5s ease;

    /* Border transitions */
    /* -webkit-transition: border .5s ease;
    -moz-transition: border .5s ease;
    transition: border .5s ease; */

    /* Positioning */
    position: relative;
    margin: 0 auto;
    float: right;
    display: flex;
    justify-content: center;
    align-items: center;

    /* Sizing */
    font-size: 16px;
    height: 100%;
    text-align: center;
    margin: 0 auto;
    padding-left: 10px;
    padding-right: 10px;

    /* Text */
    text-decoration: none;
}


a.nav-link:link,
a.nav-link:visited {
    background-color: var(--bbq-header-color);
    color: #FFF;
    border-bottom: var(--bbq-header-color) 5px solid;
}

a.nav-link:hover,
a.nav-link:active {
    background-color: var(--bbq-header-hover-color);
    color: #FFF;
    border-bottom: var(--bbq-primary-color) 5px solid;
}
</style>