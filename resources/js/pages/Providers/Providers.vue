<template>
    <div class="card" id="all-providers">
       <bread-crumb/>
        <h2 class="text-info mt-4 text-lg-center">Page Fournisseurs</h2>
        <div class="dropdown-divider"></div>
        <!-- 2 Boxes (Back, [New, List])-->
        <div class="card-body col-11 align-self-center">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <StepBox title="Nouveau Fournisseur" image="add.svg" @step="goTo" to="newProvider"
                             v-if="getUrl === 'listProvider'"></StepBox>
                    <StepBox title="Liste Fournisseurs" image="list.svg" @step="goTo" to="listProvider"
                             v-else></StepBox>
                </div>
            </div>
            <div id="change-providers-content"></div>
            <router-view :key="$route.path"></router-view>
        </div>
    </div>

</template>

<script>
    import StepBox from "../../components/Steps/StepBox";
    import BreadCrumb from "@/components/layouts/BreadCrumb";

    export default {
        name: "Providers",
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
    #all-providers {
        min-height: 100vh;
    }

</style>
