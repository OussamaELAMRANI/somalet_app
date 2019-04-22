<template>
    <div id="dashbord">
        <p>Dashboard !!</p>
        <router-link to="/login">To Login !</router-link>

        <button @click="logOut" class="btn btn-info"> Sign out</button>

    </div>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        name: "Dashbord",
        methods: {
            logOut() {
                this.signOut()
                    .then(res => {
                        this.$router.push('/login')
                    })
                    .catch(err => console.log(err))
            },

            ...mapActions({
                loadIt: 'loadStats',
                userInfos: 'userInfo',
                signOut: 'logout'
            }),

        },
        mounted() {
            this.loadIt();
            this.userInfos();
        }
    }
</script>

<style scoped>

</style>
