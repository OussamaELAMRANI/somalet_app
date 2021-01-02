<template>
   <div id="new-container">
      <big-title title="Ajouter un nouveau contenaire" position="text-left" classes="text-secondary my-4"/>
      <div class="row">
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
                     <small class="text-danger" v-if="error">Ce Contenaire est déjà éxiste !</small>
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
                        <td>{{ i + 1 }}</td>
                        <td>{{ p.name }}</td>
                        <td>{{ p.qte_facture }} <small
                           class="font-weight-bolder">{{ p.unit | getByChoice('qantity') }}</small></td>
                        <td>{{ p.rapport_qte }} <small
                           class="font-weight-bolder">{{ p.unit | getByChoice('rapport') }}(s)</small></td>
                        <td class="text-primary font-weight-bolder">
                           {{ p.rapport_qte * p.qte_facture }}
                           {{ p.unit | getByChoice('rapport') }}s
                        </td>
                        <td>
                           <button class="btn btn-outline-danger btn-sm"
                                   @click="deleteItem(p.product_id,p.rapport_qte)"><i
                              class="fa fa-trash"></i></button>
                        </td>
                     </tr>
                     </tbody>
                  </table>
               </div>
               <div class="col-3 text-right">
                  <button class="btn btn-success mr-3" @click="addContainer" v-if="selected_items.length !== 0">Ajouter
                     Arrivage
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
                     {{
                        noData ? "Aucun produit trouvée" :
                           "Chercher par nom de produit"
                     }}
                  </template>
               </cool-select>
            </div>
            <hr>
            <div class="row">
               <div class="col">
                  <div class="form-group ">
                     <label>Quantité ({{ unity.qantity }})</label>
                     <input type="number" class="form-control" v-model="products_items.qte_facture">
                  </div>
               </div>
               <div class="col">
                  <div class="form-group ">
                     <label>Rapport par ({{ unity.rapport }}) </label>
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
import {CoolSelect} from 'vue-cool-select'
import Datepicker from 'vuejs-datepicker';
import {fr} from 'vuejs-datepicker/dist/locale'
import moment from 'moment'
import {mapActions, mapGetters} from 'vuex'

export default {
   name: "NewContainer",
   data() {
      return {
         fr,
         error: null,
         container: {
            provider_id: '',
            n_dossier: '',
            // n_facture: '',
            date_facture: ''
         },
         products_items: {
            // name: '',
            product_id: 0,
            qte_facture: 0,
            rapport_qte: 0,
            price_unit_ht: 0,
            // price_unit_ttc: 0
         },
         products: [],
         providers: [],
         loading: false,
         noData: false,
         selected_items: [],
         unity: {qantity: '', rapport: ''}
      }
   },
   computed: {
      ...mapGetters({getProviders: 'getProviders'})
   },
   mounted() {
      this.loadProviders().then(data => this.providers = (data) ? this.getProviders : []);
   },
   methods: {
      onSearch(search) {
         const lettersLimit = 2;
         this.noData = false;

         if (search.length < lettersLimit) {
            this.products = [];
            this.loading = false;
            return;
         }
         this.loading = true;

         axios.get(`/api/products/search`, {
               params: {
                  by: 'name',
                  value: search,
                  with: 'unit'
               }
            }
         ).then(({data}) => {
            // console.log(data.data)
            this.products = data.data;
            if (data.length === 0)
               this.noData = true

            this.loading = false;
         })
      },
      push_item() {
         let product = _.cloneDeep(this.products_items)
         const productName = _.filter(this.products, (p) => {
            return p.id === product.product_id
         })
         const name = {name: productName[0].name, unit: this.unity}
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
      deleteItem(id, rapport) {
         this.selected_items = this.selected_items.filter(i => {
            return (id != i.product_id || rapport != i.rapport_qte)
         })
      },
      addContainer() {
         const container = this.container
         const products = this.selected_items.map(p => _.omit(p, ['unit']))
         container.date_facture = moment(container.date_facture).toDate();
         axios.post('/api/containers', {container, products})
            .then(({data}) => {
               console.log(data)
               this.$router.push({name: 'list_container'})
            })
            .catch(error => {
               console.log(error.response)
               this.error = error.response.data
            })
      },
      ...mapActions({
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
      'products_items.product_id': function (product_id) {
         const product = _.filter(this.products, (p) => {
            return p.id === product_id
         })

         if (product.length > 0) {
            const {unit} = product[0];
            let {name} = unit;
            this.unity = {
               qantity: name.substring(0, name.indexOf('/')),
               rapport: name.substring(name.indexOf('/') + 1)
            }
         }
      }
      // 'container.date_facture': function (d) {
      //     this.container.date_facture = moment(d).toDate();
      // }
   },
   components: {BigTitle, CoolSelect, Datepicker}
}
</script>

<style scoped>

</style>
