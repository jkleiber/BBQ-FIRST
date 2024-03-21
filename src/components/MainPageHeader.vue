<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
    <div class="nav">
        <RouterLink to="/" class="nav"></RouterLink>

        <!--Right Aligned Elements-->
        <!-- <RouterLink to="/help" class="nav_txt">Help</RouterLink> -->

        <RouterLink to="/team" class="nav_txt">Teams</RouterLink>


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
a.nav {
    background-image: url("/assets/navicon.png");
}

a.nav_txt {
    transition: color .5s ease;
    transition: background-color .5s ease;
    -ms-transition: color .5s ease;
    -ms-transition: background-color .5s ease;
    -moz-transition: color .5s ease;
    -moz-transition: background-color .5s ease;
    -webkit-transition: color .5s ease;
    -webkit-transition: background-color .5s ease;
}


a.nav_txt:link,
a.nav_txt:visited {

    -webkit-transition: border .5s ease;
    -moz-transition: border .5s ease;
    transition: border .5s ease;
    position: relative;
    /* background-color: #FF7A00; */
    text-decoration: none;
    color: #FFF;
    margin: 0 auto;
    float: right;
    font-size: 16px;
    padding: 14.5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    border-radius: 5px;
    margin: 10;
}

a.nav_txt:hover,
a.nav_txt:active {
    background-color: --md-sys-color-primary;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    text-decoration: none;
    margin: 0 auto;
    color: #FFF;
    float: right;
    font-size: 16px;
    padding: 14.5px;
    margin: 10;
}
</style>