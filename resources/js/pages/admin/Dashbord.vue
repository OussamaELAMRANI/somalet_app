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
    import {mapActions} from 'vuex'
    import LeftBar from "../../components/Admin/LeftBar";
    import NavBar from "../../components/Admin/NavBar";

    export default {
        name: "Dashbord",
        components: {NavBar, LeftBar},
        methods: {
            // logOut() {
            //     this.signOut()
            //         .then(res => {
            //             this.$router.push('/login')
            //         })
            //         .catch(err => console.log(err))
            // },
            //
            // ...mapActions({
            //     loadIt: 'loadStats',
            //     userInfos: 'userInfo',
            //     signOut: 'logout'
            // }),

        },
        mounted() {
            // this.loadIt();
            // this.userInfos();
        }
    }
</script>

<style scoped>

</style>
