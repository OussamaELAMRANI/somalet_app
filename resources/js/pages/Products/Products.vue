<template lang="pug">
    #Products
        big-title(title="Gestion des Produits")
        //.row.d-flex.justify-content-center
            //.col-3
            //    StepBox(title="Retour", image='back.svg' @step="goTo" to="listProducts" )
            .col-3.d-flex.justify-content-center
                StepBox(title="Nouveau Produit", image='add.svg' @step="goTo" to="newProduct" v-if="getUrl === 'listProducts'")
                StepBox(title="Liste Produits", image='list.svg' @step="goTo" to="listProducts" v-else)
        router-view(:key="$route.path")
</template>

<script>
    import StepBox from "../../components/Steps/StepBox";
    import BigTitle from "@/components/layouts/BigTitle";

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
        components: {BigTitle, StepBox}
    }
</script>

<style scoped>
    #Products{
        min-height: 100vh;
    }
</style>
