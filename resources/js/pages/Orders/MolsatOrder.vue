<template>
   <div id="molsat-order" class="container">
      <table class="table table-striped text-center">
         <thead>
         <tr>
            <th>#</th>
            <th>Produit</th>
            <th>Qte</th>
            <th>Couleurs</th>
            <th>Pointures</th>
         </tr>
         </thead>
         <tbody>
         <tr v-for="(o,i) in order">
            <td>{{i+1}}</td>
            <td>{{o.product}}</td>
            <td>{{o.qte}}</td>
            <td>
               <div class="square__long shadow rounded my-3 mx-1" v-if="o !== null"
                    :style="o.color|getColor">
               </div>
            </td>
            <td>
               <div class="square shadow rounded my-2 mx-1 text-center d-inline-block bg-success text-white"
                    v-for="s in o.sizes">
                  {{s.size}}
               </div>
            </td>
         </tr>
         <tr>
            <td colspan="6" class="bg-secondary"></td>
         </tr>
         </tbody>
         <tfoot>

         <tr class="segment">
            <td></td>
            <td class="d-flex align-items-center" style="height: 100px;">
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
            <td class="align-middle" style="max-width: 50px">
               <input type="number" class="form-control" v-model="qte">
            </td>
            <td class="align-middle" style="max-width: 50px">
               <div class="square__long shadow rounded my-3 mx-1" v-if="tempo !== null"
                    :style="tempo.color|getColor">
               </div>
            </td>
            <td class="align-middle" style="max-width: 150px">
               <multiselect v-model="sizes" :options="getSizes" :multiple="true" :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true" placeholder="Pick some" label="size" track-by="size"
                            :preselect-first="true">
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                     <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span>
                  </template>
               </multiselect>
               <div class="square shadow rounded my-2 mx-1 text-center d-inline-block bg-success text-white"
                    v-for="s in sizes">
                  {{s.size}}
               </div>

            </td>
         </tr>
         <tr>
            <td colspan="5" class="text-right">
               <button class="btn btn-success" @click="insertOrder">
                  <i class="fa fa-plus"></i>
               </button>
               <button class="btn btn-danger">
                  <i class="fa fa-window-close"></i>
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
            getSizes: []
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
            })
         },
         insertOrder() {
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
</style>
