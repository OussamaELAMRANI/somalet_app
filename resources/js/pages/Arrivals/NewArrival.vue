<template>
    <form-wizard title="Gestion d'arrivages" subtitle="Nouveau arrivage" :nextButtonText="'Suivant'">
        <tab-content title="Entete de la facture" :before-change="entetePass">
            <!--            <arrival-form ref="entete" @getCharge="getCharge" @getArrivalId="getArrivalId"/>-->
            <arrival-new ref="entete" @getCharge="getCharge"/>
        </tab-content>
        <tab-content title="Ajout des produits" :before-change="producsPass">
            <!--            <arrival-form-products ref="formProduct" "@getProducts="getProducts" :type="ArrivalData.type" :arrival="ArrivalData"/>-->
            <arrival-form-products ref="formProduct" :arrival="ArrivalsData" @getProducts="getProducts"
                                   :type="ArrivalsData.type"/>
        </tab-content>
        <tab-content title="Résultat">
            <h3 class="text-center text-uppercase text-secondary">Confirmation</h3>
            <hr/>
            <div class="row justify-content-center">
                <div class="col-3">
                    <button class="btn btn btn-outline-success" @click="saveArrivals">Valider l'arrivage</button>
                    <button class="btn btn btn-outline-secondary" @click="goToArrivalList()">Annuler</button>
                </div>
            </div>
        </tab-content>
    </form-wizard>
</template>

<script>
    import ArrivalForm from "../../components/Arrivals/ArrivalForm";
    import ArrivalFormProducts from "../../components/Arrivals/ArrivalFormProducts";
    import ArrivalNew from "../../components/Arrivals/ArrivalNew";

    export default {
        name: "NewArrival",
        components: {ArrivalNew, ArrivalForm, ArrivalFormProducts},
        data() {
            return {
                charges: [],
                arrivalId: null,
                //donnee de l'arrivage
                ArrivalData: {},

                ArrivalsData: {}

            }
        },
        methods: {
            entetePass() {
                this.$refs.formProduct.reset();
                return this.$refs.entete.validateBeforeSubmit();
            },
            producsPass() {
                return this.$refs.formProduct.validateBeforeSubmit()
            },
            //get first step DATA
            getCharge(charge) {
                this.ArrivalsData = charge
            },
            //get secondary step
            getProducts(products) {
                this.ArrivalsData['products'] = products;
            },
            goToArrivalList() {
                this.$router.push({name: 'list_arrivals'})
            },
            saveArrivals() {
                let arrivals = _.cloneDeep(this.ArrivalsData)
                let products = _.cloneDeep(arrivals.products)
                arrivals = _.omit(arrivals, ['products'])

                axios.post('/api/arrivals', {arrivals, products})
                    .then(({data}) => {
                        console.log(data)
                        this.$router.push({name: 'list_arrivals'})
                    })
            }
        }

    }
</script>

<style scoped>

</style>
