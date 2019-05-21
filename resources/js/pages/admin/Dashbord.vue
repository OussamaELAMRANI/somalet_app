<template>
    <div id="dashbord">
        <NavBar/>
        <div class="left">
            <left-bar/>
        </div>
        <main class="container">
            <router-view/>
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

<style lang="scss">
</style>
