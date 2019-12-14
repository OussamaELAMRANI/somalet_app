<template>
    <div id="dashbord">
        <NavBar/>
<!--        <div class="left">-->
<!--            -->
<!--        </div>-->
        <main class="main">
            <left-bar/>
            <div class="container-fluid">
                <router-view :key="$route.path"/>
            </div>
        </main>
    </div>
</template>

<script>
    // import {mapActions} from 'vuex'
    import LeftBar from "../../components/Admin/LeftBar";
    import NavBar from "../../components/Admin/NavBar";
    export default {
        name: "Dashbord",
        components: {NavBar, LeftBar},
        methods: {},
        mounted() {
           let el = document.body;
           if (el.addEventListener) {
              el.addEventListener("online", function () {
                 alert("online");}, true);

              el.addEventListener("offline", function () {
                 alert("offline");}, true);
           }
           else if (el.attachEvent) {
              el.attachEvent("ononline", function () {
                 alert("online");});
              el.attachEvent("onoffline", function () {
                 alert("offline");});
           }
           else {
              el.ononline = function () {
                 alert("online");};
              el.onoffline = function () {
                 alert("offline");};
           }
        }
    }
</script>

<style>
    .main{
        margin-left: 220px;
    }
</style>
