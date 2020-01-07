<template>
   <div id="molsat-order" class="container">
      <table class="table table-striped text-center table-bordered shadow">
         <thead class="bg-primary text-white">
         <tr>
            <th>#</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Couleur</th>
            <th>Pointures</th>
            <th>Action</th>
         </tr>
         </thead>
         <tbody>
         <tr v-for="(o,i) in order">
            <th scope="row" class="align-middle">{{i+1}}</th>
            <td class="align-middle">{{o.product}}</td>
            <td class="align-middle">{{o.qte}}</td>
            <td class="align-middle">
               <div class="square__long shadow rounded my-3 mx-1" v-if="o !== null"
                    :style="o.color|getColor">
               </div>
            </td>
            <td class="align-middle">
               <div class="square shadow rounded my-2 mx-1 text-center d-inline-block bg-success text-white"
                    v-for="s in o.sizes">
                  {{s.size}}
               </div>
            </td>
            <td>
               <button class="btn btn-danger rounded-pill" @click="deleteItem(i)">
                  <i class="fa fa-trash"></i>
               </button>
            </td>
         </tr>
         <tr v-if="order.length>0">
            <td colspan="6" class="bg-secondary text-right my-0">
               <button class="btn btn-success " @click="addProductionOrder">Ajouter Cette commande</button>
            </td>
         </tr>
         </tbody>
         <tfoot>
         <tr class="segment">
            <td class="align-middle" colspan="2" style="height: 100px; text-align: center">
               <div class="col-8">
                  <cool-select :items='searchProduct' @search='onSearch' :loading='loading' item-text='name'
                               v-model='tempo'>
                     <template slot="item" slot-scope="{ item:p }">
                        <div class="d-flex">
                           <p>{{p.name}}</p>
                        </div>
                     </template>
                     <template slot="no-data">
                        {{ noData ? "Aucun produit trouvée" :"Chercher par nom de produit" }}
                     </template>

                  </cool-select>
               </div>

            </td>
            <td class="align-middle" style="max-width: 100px">
               <input type="number" class="form-control" v-model.number="qte">
            </td>
            <td class="align-middle" style="max-width: 50px">
               <div class="square__long shadow rounded my-3 mx-1" v-if="tempo !== null"
                    :style="tempo.color|getColor">
               </div>
            </td>
            <td class="align-middle" style="max-width: 150px">
               <multiselect v-model="sizes" :options="getSizes" :multiple="true" :close-on-select="false"
                            :clear-on-select="false"
                            selectedLabel="Sélectionné"
                            deselectLabel="Désélectionné"
                            selectLabel="Prendre cette pointure"
                            :preserve-search="true" placeholder="Choisissez-en 👞" label="size" track-by="size"
                            :preselect-first="true">
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                     <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Pointures sont sélectionnés</span>
                  </template>
                  <template slot="noOptions">List est vide.</template>

               </multiselect>
               <div class="square shadow rounded my-2 mx-1 text-center d-inline-block bg-success text-white"
                    v-for="s in sizes">
                  {{s.size}}
               </div>
            </td>
            <td class="align-middle">
               <button class="btn btn-success" id="btn__insert" @click="insertOrder">
                  <i class="fa fa-plus"></i>
               </button>
            </td>
         </tr>
         </tfoot>
      </table>


   </div>
</template>

<script>

   import {CoolSelect} from 'vue-cool-select'

   export default {
      components: {CoolSelect},
      name: "MolsatOrder",
      data() {
         return {
            products: [],
            searchProduct: [],
            tempo: {
               sizes: []
            },
            loading: false,
            noData: true,
            order: [],
            sizes: [],
            qte: 0,
            getSizes: [],
            production_order: []
         }
      },
      methods: {
         onSearch(search) {
            const lettersLimit = 2;

            this.noData = false;
            if (search.length < lettersLimit) {
               this.searchProduct = [];
               this.loading = false;
               return;
            }
            this.loading = true;

            axios.get(`/api/products/${search}/search`, {
               params: {
                  with: 'unit,provider,color,category,arrivals,sizes'
               }
            }).then(({data}) => {
               this.searchProduct = data;
               if (data.length === 0)
                  this.noData = true

               this.loading = false;
            }).catch(err => console.log(err))
         },
         insertOrder() {
            if (this.tempo == null || this.tempo.length < 1)
               return;

            const info = _.cloneDeep(this.tempo);
            const order = {
               product: info.name,
               qte: this.qte,
               sizes: this.sizes,
               color: info.color,
            }
            this.order.unshift(order)
            this.tempo = []
            this.sizes = []
         },
         deleteItem(index) {
            this.order.splice(index, 1)
         },
         addProductionOrder() {
            axios.post(`/api/receptions/production`,
               {
                  production_order: this.production_order
               }
            ).then(({data}) => {
               console.log(data)
               this.$router.push({name: 'listOrderProduction'})
            }).catch(err => console.log(err))

         }
      },
      filters: {
         getColor: function (c) {
            return (c == null) ? '' : `background-color: ${c.color}`;
         }
      },
      watch: {
         tempo: function (t) {
            if (_.has(t, 'sizes')) {
               this.getSizes = t.sizes
            } else this.getSizes = []
         },
         order: function (o) {
            const production_order = [];
            _.forEach(o, function (c) {
               const order_quantity = c.qte;
               _.forEach(c.sizes, (s) => {
                  production_order.push({
                     ps_id: s.id,
                     order_quantity: order_quantity,
                     fabric_quantity: 0,
                  })
               })
            })
            this.production_order = production_order;
         }
      }
   }
</script>

<style lang="scss" scoped>
   .square {
      height: 25px;
      width: 25px;

   }

   .square__long {
      height: 25px;
      width: 100%;
   }

   #btn__insert:hover {
      animation: rotations 5s 2;
   }

   @keyframes rotations {
      0% {
         transform: rotate(72deg);
      }
      25% {
         transform: rotate(144deg);
      }
      50% {
         transform: rotate(216deg);
      }
      75% {
         transform: rotate(288deg);
      }
      100% {
         transform: rotate(0deg);
      }
   }

</style>
