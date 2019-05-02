<template>
    <form-wizard title="Gestion d'arrivages" subtitle="Nouveau arrivage">
        <tab-content title="Entete de la facture">
            <arrival-form/>
        </tab-content>
        <tab-content title="Ajout des produits">
            My second tab content
        </tab-content>
        <tab-content title="resumer">
            Yuhuuu! This seems pretty damn simple
        </tab-content>
    </form-wizard>
</template>

<script>
    import ArrivalForm from "../../components/Arrivals/ArrivalForm";

    export default {
        name: "NewArrival",
        components: {ArrivalForm}
    }
</script>

<style scoped>

</style>
