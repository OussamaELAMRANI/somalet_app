<template>
    <form-wizard title="Gestion d'arrivages" subtitle="Nouveau arrivage" :nextButtonText="'Suivant'">
        <tab-content title="Entete de la facture" :before-change="entetePass">
            <arrival-form ref="entete"/>
        </tab-content>
        <tab-content title="Ajout des produits">
            <arrival-form-products ref="formProduct"/>
        </tab-content>
        <tab-content title="resumer">
            Yuhuuu! This seems pretty damn simple
        </tab-content>
    </form-wizard>
</template>

<script>
    import ArrivalForm from "../../components/Arrivals/ArrivalForm";
    import ArrivalFormProducts from "../../components/Arrivals/ArrivalFormProducts";

    export default {
        name: "NewArrival",
        components: {ArrivalForm,ArrivalFormProducts},
        methods:{
            entetePass() {
                return this.$refs.entete.validateBeforeSubmit();
            },
        }

    }
</script>

<style scoped>

</style>
