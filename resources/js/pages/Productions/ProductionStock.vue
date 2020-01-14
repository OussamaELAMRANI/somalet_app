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
               </tr>
               </thead>
               <tbody class="segment">
               <tr v-if="isLoading">
                  <td colspan="5" class="p-0">
                     <div class="row justify-content-center bg-white">
                        <div class="col-2">
                           <half-circle-spinner color="#f83896" :size="40"/>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr v-if="searchIventories.length === 0">
                  <td colspan="5">
                     <h5 class="text-uppercase text-center text-secondary">0 Produits</h5>
                  </td>
               </tr>

               <template v-for="inv in searchIventories">

                  <tr v-for="(q,j) in inv.qte" class="border-0">
                     <template v-if="j % inv.qte.length ===0">
                        <td class="align-middle" :rowspan="inv.qte.length">
                           <h5 class="text-uppercase"> {{inv.name}}</h5>
                        </td>
                        <td class="align-middle" :rowspan="inv.qte.length" v-if="roles.includes('ADMINE')">
                           <h5 class="text-primary">
                              {{inv.buy}} DH
                           </h5>
                        </td>
                        <td class="align-middle" :rowspan="inv.qte.length">
                           <h5>
                              {{inv.sell}} DH
                           </h5>
                        </td>
                     </template>
                     <td>{{q.size}}</td>
                     <td>
                        {{q.order_quantity}}
                        <span class="badge">PAIRE</span>
                     </td>
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
         axios.get(`/api/inventories/production/detail`)
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
               this.isLoading = false
               return
            }

            axios.get(`/api/inventories/production/detail/${ref}`)
               .then(({data}) => {
                  this.searchIventories = data
                  this.isLoading = false
               })
         }
      },
      watch: {
         reference: function (ref) {
            if (ref !== '')
               this.isLoading = true
         }
      }
   }
</script>

<style scoped>

</style>
