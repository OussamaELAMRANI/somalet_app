<template>
   <div id="update-container">
      <big-title title="Modifier un contenaire" position="text-left" classes="text-secondary my-4"/>
      <div class="row">
         <div class="col-12" v-show="isLoading">
            <loading-page/>
         </div>
      </div>
      <div class="row" v-show="!isLoading">
         <div class="col-8">
            <div class="row text-center justify-content-center">
               <div class="col-3">
                  <label>Fournisseur : </label>
                  <cool-select :items="providers" item-text="steName" item-value="id" name="provider"
                               v-model="container.provider_id"/>
               </div>
               <div class="col-3">
                  <div class="form-group">
                     <label for="n_container">Numéro de Contenaire</label>
                     <input type="text" class="form-control" v-model="container.n_dossier" id="n_container">
                     <!--<small class="text-danger" v-if="error">Ce Contenaire est déjà éxiste !</small>-->
                  </div>
               </div>
               <div class="col-3">
                  <div class="form-group">
                     <label>Date de la Facture :</label>
                     <datepicker :language="fr" :monday-first="true"
                                 class="form-control" name="date_picker" v-model="container.date_facture"/>
                  </div>
               </div>
            </div>
            <hr>
            <div class="row justify-content-end">
               <div class="col-12">
                  <table class="table text-center table-hover table-striped">
                     <thead class="bg-primary text-white">
                     <tr>
                        <th>#</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Rapport de quantité</th>
                        <th>Total Qte</th>
                        <th>Supprimer</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr v-for="(p, i) in selected_items">
                        <td>{{i+1}}</td>
                        <td>{{p.name}}</td>
                        <td>{{p.qte_facture}}</td>
                        <td>{{p.rapport_qte}}</td>
                        <td class="text-primary font-weight-bolder">{{p.rapport_qte*p.qte_facture}}</td>
                        <td>
                           <button class="btn btn-outline-danger btn-sm"
                                   @click="deleteItem(p.product_id,p.rapport_qte)"><i
                              class="fa fa-trash"/></button>
                        </td>
                     </tr>
                     </tbody>
                  </table>
               </div>
               <div class="col-3 text-right">
                  <button class="btn btn-primary btn-block rounded-pill"
                          @click="updateContainer">
                     <i class="fa fa-wrench"/> Modifier ce contenaire
                  </button>
               </div>
            </div>

         </div>
         <div class="col-4 segment">
            <h5 class="text-primary text-uppercase">Rechercher et ajouter Produit</h5>
            <hr>
            <div class="form-group ">
               <label>Produit :</label>
               <cool-select :items="products" @search="onSearch" :loading="loading" ref="cool"
                            item-text="name" item-value="id" :name="'produit'"
                            v-model="products_items.product_id">
                  <template slot="item" slot-scope="{ item:p }">
                     <div class="d-flex">
                        <div>
                           <p>{{ p.name }} - {{ p.reference }}</p>
                        </div>
                     </div>
                  </template>
                  <template slot="no-data">
                     {{ noData ? "Aucun produit trouvée" :
                     "Chercher par nom de produit" }}
                  </template>
               </cool-select>
            </div>
            <hr>
            <div class="row">
               <div class="col">
                  <div class="form-group ">
                     <label>Quantité :</label>
                     <input type="number" class="form-control" v-model="products_items.qte_facture">
                  </div>
               </div>
               <div class="col">
                  <div class="form-group ">
                     <label>Rapport (Rouleaux..) </label>
                     <input type="number" class="form-control" v-model="products_items.rapport_qte">
                  </div>
               </div>
            </div>
            <hr>
            <button class="btn btn-outline-primary btn-block rounded-pill" @click="push_item">
               <i class="fa fa-plus"></i> Ajouter
            </button>
         </div>

      </div>


   </div>
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import {mapActions, mapGetters} from 'vuex'
   import LoadingPage from "@/components/layouts/LoadingPage";
   import {CoolSelect} from 'vue-cool-select'
   import store from '@/store'
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import moment from 'moment'

   export default {
      name: "UpdateContainer",
      components: {LoadingPage, BigTitle, CoolSelect, Datepicker},
      mounted() {
         this.loadProviders().then(data => this.providers = (data) ? this.getProviders : []);
         this.oldContainer();
      },
      computed: {
         ...mapGetters({getProviders: 'getProviders'})
      },
      data() {
         return {
            fr,
            isLoading: true,
            loading: false,
            noData: false,
            container: {provider_id: ''},
            providers: [],
            error: [],
            container_id: this.$route.params.id,
            products: [],
            products_items: {
               product_id: 0,
               qte_facture: 0,
               rapport_qte: 0,
               price_unit_ht: 0,
            },
            selected_items: []
         }
      },
      methods: {
         oldContainer() {
            const {id} = this.$route.params;
            const router = this.$router;
            this.load({
               url: `/api/containers/${id}/detail`,
               params: {},
               router
            })
               .then(data => {
                  this.container = data;
                  this.getSelectedProducts(data.product);
                  this.isLoading = false
               })
               .catch(err => {
                  console.log(err)
                  router.push({name: 'notFound'});
               })
         },
         updateContainer() {
            const container = this.container
            if (!moment(container.date_facture, 'YYYY-MM-DD').isValid())
               container.date_facture = moment(container.date_facture).calendar();
            const products = this.selected_items

            axios.put(`/api/containers/${this.container_id}/update`,
               {container, products})
               .then(({data}) => {
                  console.log(data)
                  this.$router.push({name: 'list_container'})
               })
               .catch(error => {
                  console.log(error.response)
                  this.error = error.response.data
               })
         },
         onSearch(search) {
            const lettersLimit = 2;
            this.noData = false;

            if (search.length < lettersLimit) {
               this.products = [];
               this.loading = false;
               return;
            }
            this.loading = true;

            axios.get(`/api/products/search/${search}`, {params: {by: 'name'}})
               .then(({data}) => {
                  // console.log(data.data)
                  this.products = data.data;
                  if (data.length === 0)
                     this.noData = true

                  this.loading = false;
               })
         },
         deleteItem(id, rapport) {
            this.selected_items = this.selected_items.filter(i => {
               return (id != i.product_id || rapport != i.rapport_qte)
            })
         },
         push_item() {
            let product = _.cloneDeep(this.products_items)
            const productName = _.filter(this.products, (p) => {
               return p.id === product.product_id
            })
            const name = {name: productName[0].name}
            if (product.product_id !== 0 || product.qte_facture !== 0 && product.rapport_qte !== 0) {
               product = {...name, ...product}
               this.selected_items.unshift(product)
               this.products_items = {
                  ...{
                     product_id: 0,
                     qte_facture: 0,
                     rapport_qte: 0,
                     price_unit_ht: 0,
                     // price_unit_ttc: 0
                  }
               }
            }
         },
         getSelectedProducts(products) {
            const product = [];
            products.map(function (p) {
               const {pivot} = p;
               product.push({
                  name: p.name,
                  product_id: pivot.product_id,
                  qte_facture: pivot.qte_facture,
                  rapport_qte: pivot.rapport_qte,
                  price_unit_ht: pivot.price_unit_ht,
               })
            });
            this.selected_items = product
         },
         ...mapActions({
            load: 'updateStore/getData',
            loadProviders: 'providerStore/getProviders'
         }),
      },
      watch: {
         'products_items.qte_facture': function (q) {
            this.products_items.qte_facture = Number(q)
         },
         'products_items.rapport_qte': function (q) {
            this.products_items.rapport_qte = Number(q)
         },

      },

   }
</script>

<style scoped>

</style>
