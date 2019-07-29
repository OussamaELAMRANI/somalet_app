<template>
    <div id="arrival_core">
        <template v-if="isLoading">
            <loading-page/>
        </template>
        <template v-else>
            <form-wizard title="Gestion d'arrivages" subtitle="Nouveau arrivage" :nextButtonText="'Suivant'"
                         back-button-text="Précedant" class="">
                <tab-content title="Entete de la facture" :before-change="entetePass">

                    <arrival-new ref="entete" @getCharge="getCharge" @getProduct="getProduct"/>

                </tab-content>
                <tab-content title="Ajout des produits" :before-change="producsPass">
                    <arrival-form-products ref="formProduct" :arrival="ArrivalsData" @getProducts="getProducts"
                                           :type="ArrivalsData.type" :pproduct="producty"/>
                </tab-content>
                <tab-content title="Résultat">
                    <h3 class="text-center text-uppercase text-secondary">Confirmation</h3>
                    <hr/>
                    <div class="row justify-content-center">
                        <div class="col-3">
                            <button class="btn btn btn-outline-success" @click="saveArrivals">Valider l'arrivage
                            </button>
                            <button class="btn btn btn-outline-secondary" @click="goToArrivalList()">Annuler</button>
                        </div>
                    </div>
                </tab-content>
            </form-wizard>
        </template>
    </div>


</template>

<script>
    import ArrivalForm from "../../components/Arrivals/ArrivalForm";
    import ArrivalFormProducts from "../../components/Arrivals/ArrivalFormProducts";
    import ArrivalNew from "../../components/Arrivals/ArrivalNew";
    import LoadingPage from "@/components/layouts/LoadingPage";

    export default {
        name: "NewArrival",
        components: {LoadingPage, ArrivalNew, ArrivalForm, ArrivalFormProducts},
        data() {
            return {
                isLoading: true,
                charges: [],
                arrivalId: null,
                ArrivalsData: {},
                updateArrival: {},
                producty: []
            }
        },
        mounted() {
            axios.get(`/api/arrivals/${this.$route.params.id}`)
                .then(res => {
                    this.isLoading = false
                    console.log(res.data)
                })
                .catch(err => {
                    this.isLoading = true
                    const _this = this
                    const waity = setInterval(() => {
                        _this.isLoading = false
                        _this.$router.push({name: 'notFound'});
                        console.log(err.response)
                        clearInterval(waity)
                    }, 50);
                })
        },
        methods: {
            getProduct(p) {
                console.log(p)
                this.producty = p
            },
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
                    .catch(err => console.log(err.response))
            }
        }
    }
</script>

<style scoped>

</style>
