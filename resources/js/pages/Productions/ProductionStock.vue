<template>
   <div id="production-stock">
      <big-title title="STOCK DE PRODUCTION" position="text-right" classes="text-primary h2"/>
      <div class="row">

         <div class="col-9">
            <table class="table table-hover table-striped text-center table-bordered">
               <thead class="bg-primary text-white text-uppercase table-bordered">
               <tr>
                  <th>Designation</th>
                  <th v-if="roles.includes('ADMINE')">Prix d'achat</th>
                  <th>Prix de Vente</th>
                  <th>Pointures</th>
                  <th>Quantites</th>
                  <!--                  <th v-if="roles.includes('ADMINE')">CR HT</th>-->
               </tr>
               </thead>
               <tbody class="segment">
               <tr v-if="isLoading">
                  <td colspan="6" class="p-0">
                     <div class="row justify-content-center bg-white">
                        <div class="col-2">
                           <half-circle-spinner color="#f83896" :size="40"/>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr v-if="Object.keys(searchIventories).length === 0">
                  <td colspan="4">
                     <h5 class="text-uppercase text-center text-secondary">0 Produits</h5>
                  </td>
               </tr>

               <template v-for="p in Object.keys(searchIventories)">

                  <tr v-for="(inv, j) in inventories[p]">
                     <template v-if="j%inventories[p].length === 0">
                        <td :rowspan="inventories[p].length" class="align-middle">
                           <h5> {{p}}</h5>
                        </td>
                        <td :rowspan="inventories[p].length" class="align-middle" v-if="roles.includes('ADMINE')">
                           <h5>{{inv.buy_price}} DH</h5>
                        </td>
                        <td :rowspan="inventories[p].length" class="align-middle">
                           <h5>{{inv.sell_price}} DH</h5>
                        </td>
                     </template>
                     <td class="align-middle">{{inv.size}}</td>
                     <td class="align-middle">{{inv.order_quantity}}</td>
                  </tr>

               </template>
               </tbody>
            </table>
         </div>
         <div class="col">
            <div class="segment shadow">
               <h5 class="text-primary">Filter par:</h5>
               <hr>
               <div class="form-group">
                  <label>Produit</label>
                  <input type="text" class="form-control rounded-pill" placeholder="Désignation ..."
                         @input="getInventoriesByRef" v-model="reference">
               </div>
               <hr>
            </div>
         </div>

      </div>
   </div>
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import {HalfCircleSpinner} from 'epic-spinners'
   import store from "@/store";

   export default {
      name: "ProductionStock",
      components: {BigTitle, HalfCircleSpinner},
      data() {
         return {
            reference: '',
            isLoading: false,
            inventories: [],
            searchIventories: [],
            roles: store.getters.roles
         }
      },
      mounted() {
         axios.get('/api/inventories/production/detail')
            .then(({data}) => {
               this.inventories = data
               this.searchIventories = data
            })
      },
      methods: {
         getInventoriesByRef() {
            let ref = this.reference;
            ref = ref.split('/').join('.');
            ref = ref.split(`\\`).join('.');

            if (ref === '') {
               this.searchIventories = this.inventories;
               return
            }

            axios.get(`/api/inventories/production/search/${ref}`)
               .then(({data}) => {
                  this.searchIventories = data
                  this.isLoading = false
               })
         }
      },
      watch: {
         reference: function (ref) {
            // this.isLoading = true
         }
      }
   }
</script>

<style scoped>

</style>
