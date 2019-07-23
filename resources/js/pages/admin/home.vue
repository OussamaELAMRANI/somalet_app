<template>
    <div id="home">
        <h3 class="text-center text-uppercase text-secondary m-5 ar">Dashboard</h3>
        <hr>
        <div class="row">
            <div class="col-4">
                <Box img="boxes/add.svg" title="Ajouter"/>
            </div>
        </div>

    </div>
</template>

<script>
    import Box from "../../components/utils/Box";
    // import store from '@/store'

    export default {
        name: "home",
        components: {Box},
        data() {
            return {
                // my_routes: store.getters.permission_routes
            }
        },
        computed: {
            // filterRoutes() {
            //     const routes = _.cloneDeep(this.my_routes)
            //     return _.filter(routes, (r) => r.hidden !== true)
            // }
        }
    }
</script>

<style scoped>

</style>
