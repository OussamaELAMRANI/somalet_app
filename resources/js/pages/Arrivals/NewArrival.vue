<template>
    <form-wizard title="Gestion d'arrivages" subtitle="Nouveau arrivage" :nextButtonText="'Suivant'">
        <tab-content title="Entete de la facture" :before-change="entetePass">
            <!--            <arrival-form ref="entete" @getCharge="getCharge" @getArrivalId="getArrivalId"/>-->
            <arrival-new ref="entete" @getCharge="getCharge"/>
        </tab-content>
        <tab-content title="Ajout des produits" :before-change="producsPass">
            <!--            <arrival-form-products ref="formProduct" "@getProducts="getProducts" :type="ArrivalData.type" :arrival="ArrivalData"/>-->
            <arrival-form-products ref="formProduct" :arrival="ArrivalsData" @getProducts="getProducts"/>
        </tab-content>
        <tab-content title="Résultat">
            <h3 class="text-center text-uppercase text-secondary">Bien Enregister</h3>
            <hr/>
            <div class="row justify-content-center">
                <div class="col-3">
                    <button class="btn btn btn-outline-warning" @click="saveArrivals">Valider l'arrivage</button>
                    <button class="btn btn btn-outline-success">Ajouter Nouveau Arrivage</button>
                    <button class="btn btn btn-outline-primary">Lister les arrivages</button>
                </div>
            </div>
            <div class="row">
                <code>
                    {{ArrivalsData | JSON}}
                </code>
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
            arrayRemove(arr, value) {
                return arr.filter(function (ele) {
                    return ele != value;
                });
            },
            saveArrivals() {
                let arrivals = _.cloneDeep(this.ArrivalsData)
                let products = _.cloneDeep(arrivals.products)
                _.pull(arrivals, 'products')

                axios.post('/api/arrivals', {arrivals, products})
                    .then(({data}) => {
                        console.log(data)
                    })
            }
        }

    }
</script>

<style scoped>

</style>
