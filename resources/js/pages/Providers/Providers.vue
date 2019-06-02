<template lang="pug">
    #all-providers.container
        h2.text-info.mt-4.text-lg-center Page Fournisseurs
        .dropdown-divider
        // 2 Boxes (Back, [New, List])
        .row
            .col.d-flex.justify-content-center
                StepBox(title="Nouveau Fournisseur", image='add.svg' @step="goTo" to="newProvider" v-if="getUrl === 'listProvider'")
                StepBox(title="Liste Fournisseurs", image='list.svg' @step="goTo" to="listProvider" v-else)

        #change-providers-content
        router-view(:key="$route.path")

</template>

<script>
    import StepBox from "../../components/Steps/StepBox";

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
        components: {StepBox}
    }
</script>

<style scoped>

</style>
