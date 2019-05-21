<template>
    <form-wizard title="Gestion d'arrivages" subtitle="Nouveau arrivage" :nextButtonText="'Suivant'">
        <tab-content title="Entete de la facture" :before-change="entetePass">
            <arrival-form ref="entete" @getCharge="getCharge" @getArrivalId="getArrivalId"/>
        </tab-content>
        <tab-content title="Ajout des produits" :before-change="producsPass">
            <arrival-form-products ref="formProduct" @getProducts="getProducts" :type="ArrivalData.type" :arrival="ArrivalData"/>
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
        components: {ArrivalForm, ArrivalFormProducts},
        data() {
            return {
                charges: [],
                arrivalId: null,
                //donnee de l'arrivage
                ArrivalData: {
                    provider_id: null,
                    n_facture: null,
                    n_dossier: null,
                    date_facture: null,
                    date_facture1: null,
                    price_devise: null,
                    cours_change: null,
                    price_provider: null,
                    type: '',
                    types: [],
                    //Variables des charges
                    transitaire: null,
                    transitaire_tva: null,
                    transitaire_ttc: null,
                    transport: null,
                    transport_tva: null,
                    transport_ttc: null,
                    magazinage: null,
                    magazinage_tva: null,
                    magazinage_ttc: null,
                    surestaries: null,
                    surestaries_tva: null,
                    surestaries_ttc: null,
                    manutension: null,
                    manutension_tva: null,
                    manutension_ttc: null,
                    fret: null,
                    fret_tva: null,
                    fret_ttc: null,
                    autres: null,
                    autres_tva: null,
                    autres_ttc: null,
                    cout_revient: null,
                    cout_revient_tva: null,
                    cout_revient_ttc: null,
                    //liste de la ligne produit
                    products:[]
                }
            }
        },
        methods: {
            entetePass() {
                return this.$refs.entete.validateBeforeSubmit();
            },
            producsPass() {
                return this.$refs.formProduct.validateBeforeSubmit()
            },
            //get first step DATA
            getCharge(charge) {
                for (var pair of charge.entries()) {
                    this.charges[`${pair[0]}`] = pair[1]
                }
                for(var key in this.charges){
                    let value = this.charges[key];
                    this.ArrivalData[`${key}`] = value;
                }
                console.log(this.ArrivalData);
            },
            //get secondary step
            getProducts(products){
                this.ArrivalData['products'] = products;
                alert('products bien recu');
                console.log(this.ArrivalData);
            },
            getArrivalId(v) {
                console.log(v);
                alert(v);
            }
        }

    }
</script>

<style scoped>

</style>
