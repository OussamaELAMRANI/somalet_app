<template lang="pug">
    #Products
        h2.text-info.m-4.text-lg-center Pages Produits
        .dropdown-divider
        .row.justify-content-center
            .col-3
                StepBox(title="Retour", image='back.svg' @step="goTo" to="listProducts" )
            .col-3
                StepBox(title="Nouveau Produit", image='add.svg' @step="goTo" to="newProduct" v-if="getUrl === 'listProducts'")
                StepBox(title="Liste Produits", image='list.svg' @step="goTo" to="listProducts" v-else)
        router-view
</template>

<script>
    import StepBox from "../../components/Steps/StepBox";

    export default {
        name: "Products",
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
