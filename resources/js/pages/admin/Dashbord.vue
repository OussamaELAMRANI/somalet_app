<template>
    <div id="dashbord">
        <NavBar/>
<!--        <div class="left">-->
<!--            -->
<!--        </div>-->
        <main class="main">
            <left-bar/>
            <div class="container-fluid">
                <router-view/>
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
            // this.loadIt();
            // this.userInfos();
        }

    }
</script>

<style>
    .main{
        margin-left: 50px;
    }
</style>
