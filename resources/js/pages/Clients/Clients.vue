<template>
    <div class="card" id="all-clients">
         <bread-crumb/>
        <h2 class="text-info mt-4 text-lg-center">Page Clients</h2>
        <div class="dropdown-divider"></div>
        <!-- 2 Boxes (Back, [New, List])-->
        <div class="card-body col-11 align-self-center">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <StepBox title="Nouveau Client" image="add.svg" @step="goTo" to="newClient"
                             v-if="getUrl === 'listClient'"></StepBox>
                    <StepBox title="Liste Clients" image="list.svg" @step="goTo" to="listClient"
                             v-else></StepBox>
                </div>
            </div>
            <div id="change-clients-content"></div>
            <router-view :key="$route.path"></router-view>
        </div>
    </div>

</template>

<script>
    import StepBox from "../../components/Steps/StepBox";
    import BreadCrumb from "@/components/layouts/BreadCrumb";

    export default {
        name: "Clients",
        methods: {
            goTo(nameRoute) {
                this.$router.push({name: nameRoute})
            }
        },
        computed: {
            getUrl() {
                return this.$route.name
            }
        },
        components: {BreadCrumb, StepBox}
    }
</script>

<style scoped>
    #all-clients {
        min-height: 100vh;
    }

</style>
