<template>
   <div id="arrival-form-products" class="segment">
      <!--        <code>{{pproduct}}</code>-->
      <form @submit.prevent="sendProduct">
         <big-title title="Ajout des produits"/>
         <div class="container" v-for="(product,k) in productsCalculator" :key="k">
            <div class="row">
               <div class="col">
                  <div class="form-group">
                     <label>Produit :</label>
                     <cool-select :items="prods[k]" @search="onSearch($event,k)" :loading="loading"
                                  item-text="name" item-value="id" :name="'produit'+k"
                                     v-model="product.product_id" :ref="'__'+k">
                        <template slot="item" slot-scope="{ item:p }">
                           <div class="d-flex">
                              <div>
                                 <p>{{ p.name }} - {{ p.reference }}</p>
                              </div>
                              <div>
                                 <img :src="getImg(p.img)" class="country-flag" style="width: 90px;height: 90px;"/>
                              </div>
                           </div>
                        </template>
                        <template slot="no-data">
                           {{ noData ? "Aucun produit trouvée" :
                           "Chercher par nom de produit" }}
                        </template>
                     </cool-select>
<!--                     <small class="text-danger" v-show="errors.has('produit'+k)"> Vous devez selectionner un produit-->
<!--                     </small>-->
                     <div class="dropdown-divider"></div>
                  </div>
               </div>
               <!-- Qte  Products-->
               <div class="col">
                  <div class="form-group">
                     <label>Quantité :</label>
                     <input type="number" :name="'qte'+k" class="form-control" v-model="product.qte">
<!--                     <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('qte'+k)"></i>-->
                  </div>
               </div>
               <div class="col">
                  <div class="form-group">
                     <label>Rapport de quantité:</label>
                     <input type="number" :name="'rqte'+k" class="form-control" v-model="product.rapport_qte">
                     <!--                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('rqte'+k)"></i>-->
                  </div>
               </div>

               <template v-if="type === 'INTERNATIONAL'">
                  <!--    Devis Price for each Product-->
                  <div class="col">
                     <div class="form-group">
                        <label>Prix en devise :</label>
                        <input type="number" class="form-control" v-model="product.product_devis"
                               :name="'price_devise'+k"/>
<!--                        <i class="fa fa-exclamation-triangle text-danger"-->
<!--                           v-show="errors.has('price_devise'+k)"></i>-->
                     </div>
                  </div>
                  <div class="col">
                     <div class="form-group">
                        <label>Prix de Douane :</label>
                        <input type="number" class="form-control" v-model="product.douane"
                               :name="'douane'+k" />
<!--                        <i class="fa fa-exclamation-triangle text-danger"-->
<!--                           v-show="errors.has('douane'+k)"></i>-->
                     </div>
                  </div>
                  <!--   Calculate Sell Product's price-->
                  <div class="col">
                     <div class="form-group">
                        <label for="">Cout de revient Unitaire :</label>
                        <input type="number" class="form-control" v-model="product.sell_price"
                               :name="'prix_achat'+k" disabled>
                     </div>
                  </div>
               </template>
               <template v-else>
                  <div class="col">
                     <div class="form-group">
                        <label for="">Prix d'achat Unitaire HT :</label>
                        <input type="number" :name="'prix_u_HT'+k" class="form-control"
                               v-model="product.price_unit_ht" >
<!--                        <i class="fa fa-exclamation-triangle text-danger"-->
<!--                           v-show="errors.has('prix_u_HT'+k)"></i>-->
                        <div class="dropdown-divider"></div>
                     </div>
                  </div>
                  <div class="col">
                     <div class="form-group">
                        <label for="">Prix d'achat Unitaire TTC</label>
                        <input type="number" class="form-control" v-model="product.price_unit_ttc"
                               :name="'prix_u_ttc'+k">
<!--                        <i class="fa fa-exclamation-triangle text-danger"-->
<!--                           v-show="errors.has('prix_u_ttc'+k)"></i>-->
                        <!--                        <span class="help text-danger" v-show="errors.has('prix_u_ttc')">{{ errors.first('prix_u_ttc') }}</span>-->
                        <div class="dropdown-divider"></div>
                     </div>
                  </div>
               </template>
               <div class="col">
                  <div class="form-group">
                     <label for="">Remarque :</label>
                     <textarea class="form-control rounded-0" :name="'remark'+k"
                               v-model="product.remark"></textarea>
                     <div class="dropdown-divider"></div>
                  </div>
                  <div class="form-group">
                     <button class="btn btn-sm btn-danger" @click="remove(k)">
                        <i class="fas fa-minus-circle"></i>
                     </button>
                     <button class="btn btn-sm btn-success" @click="add()">
                        <i class="fa fa-plus fa-3"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
</template>

<script>
   import {CoolSelect} from 'vue-cool-select'
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "ArrivalFormProduct",
      props: {
         type: {
            default: 'INTERNATIONAL'
         },
         arrival: {},
         pproduct: {}
      },
      data() {
         return {
            messageErr: 'Champs obligatoire !',

            productsCalculator: [
               {
                  product_id: 0,
                  qte: '',
                  rapport_qte: null,
                  product_devis: 0,
                  douane: 0,
                  cost: {
                     transitaire: 0,
                     transport: 0,
                     magazinage: 0,
                     // douane: 10,
                     manutension: 0,
                     fret: 0,
                     autres: 0,
                     surestaries: 0,
                  },
                  global_cost: 0,
                  sell_price: 0,
                  remark: '',
                  // NATIONAL
                  price_unit_ht: 0,
                  price_unit_ttc: 0,
               }
            ],
            prods: Array([]),
            // productSearch: [],
            loading: false,
            noData: false,
            // selectedProductsId: [],
            arrivals: _.cloneDeep(this.arrival)
         }
      },
      mounted() {
         if (this.$route.name === 'update_arrival') {
            console.log(this.pproduct)
         }
      },
      watch: {
         productsCalculator: {
            deep: true,
            handler(arr) {
               _.forEach(arr, (p) => {

                  _.forEach(p.cost, (v, k) => {
                     p.cost[k] = this.singleCost(this.arrival[k], p.product_devis, this.arrival['price_devise'])
                  })
                  p.global_cost = _.sum(_.values(p.cost)) + (p.product_devis * Number(this.arrival['cours_change'])) + Number(p.douane)

                  if (p.rapport_qte === null || p.rapport_qte === '')
                     p.sell_price = (p.global_cost / p.qte).toFixed(2)
                  else
                     p.sell_price = (p.global_cost / (p.qte * p.rapport_qte)).toFixed(2)
               })
            }
         }
      },
      methods: {
         reset() {
            this.productsCalculator = [{
               product_id: 0,
               qte: 1,
               rapport_qte: null,
               product_devis: 0,
               douane: 0,
               cost: {
                  transitaire: 0,
                  transport: 0,
                  magazinage: 0,
                  // douane: 10,
                  manutension: 0,
                  fret: 0,
                  autres: 0,
                  surestaries: 0,
               },
               global_cost: 0,
               sell_price: '',
               remark: '',
               // NATIONAL
               price_unit_ht: 0,
               price_unit_ttc: 0,
            }]


         },
         add() {
            this.prods.push([]);
            this.productsCalculator.push(
               {
                  product_id: 0,
                  qte: 1,
                  rapport_qte: null,
                  product_devis: 0,
                  douane: 0,
                  cost: {
                     transitaire: 0,
                     transport: 0,
                     magazinage: 0,
                     // douane: 10,
                     manutension: 0,
                     fret: 0,
                     autres: 0,
                     surestaries: 0,
                  },
                  global_cost: 0,
                  sell_price: '',
                  remark: '',
                  // NATIONAL
                  price_unit_ht: 0,
                  price_unit_ttc: 0,
               }
            );
         },
         remove(index) {
            this.productsCalculator.splice(index, 1);
            this.prods[index] = [];
         },
         getImg(img) {
            try {
               return `/storage/${img}`;
            } catch (e) {
               // return require("../assets/undefined.svg");
            }
         },
         /**
          *
          * @param chargeArrivalType Like {fret, magazinage, ...}
          * @param productDevice
          * @param totalDevis
          * @returns {number}
          */
         singleCost(chargeArrivalType, productDevise, totalDevis) {
            return (Number(chargeArrivalType) * Number(productDevise)) / Number(totalDevis)
         },
         sendProduct() {

            let products = _.cloneDeep(this.productsCalculator)
            const filterProduct = []

            _.forEach(products, (p) => {
               filterProduct.push({
                  product_id: p.product_id,
                  qte_facture: Number(p.qte),
                  rapport_qte: (p.rapport_qte !== null) ? Number(p.rapport_qte) : null,
                  fret_douane: p.douane,
                  price_unit_ht: (this.type === "NATIONAL") ? Number(p.price_unit_ht) : Number(p.sell_price),
                  price_unit_ttc: Number(p.price_unit_ttc),
                  price_devise: p.product_devis,
                  remark: p.remark,
               })
            })
            return filterProduct
         },
         onSearch(search, id) {
            let rejectId = ""
            const lettersLimit = 2;

            // console.log(search)
            // console.log(id)
            // return;
            // _.forEach(this.productsCalculator, p => {
            //     if (p.product_id)
            //         rejectId += p.product_id + ","
            // })

            this.noData = false;
            if (search.length < lettersLimit) {
               this.prods[id] = [];
               this.loading = false;
               return;
            }
            this.loading = true;

            axios.get('/api/search/products', {
               params: {
                  products: search,
                  reject: rejectId
               }
            })
               .then(({data}) => {
                  this.prods[id] = data;
                  if (data.length === 0)
                     this.noData = true

                  this.loading = false;
               })
         },

         validateBeforeSubmit() {
            // this.calculerCtRevient(this.arrival);
            return this.$validator.validateAll().then((result) => {
               if (result)
                  this.$emit('getProducts', this.sendProduct());

               return result;
            });
         },

      },
      components: {
         BigTitle,
         CoolSelect
      }
   }
</script>
