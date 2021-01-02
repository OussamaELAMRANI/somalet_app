<template>
   <div id="inventories" class="">

      <big-title title="Stock des produits" position="text-left" classes="text-primary"/>
      <div class="row">
         <div class="col-9" id="printMe">
            <table class="table table-hover table-bordered table-striped text-center">
               <thead class="bg-primary text-white text-uppercase small table-bordered">
               <tr>
                  <th>Reference</th>
                  <th>Designation</th>
                  <th>Quantité</th>
                  <th>Rapport</th>
                  <th>Total</th>
                  <th v-if="roles.includes('ADMINE')">CR HT</th>
                  <th>Prix de Vente</th>
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
               <tr v-if="searchIventories.length == 0">
                  <td colspan="6">
                     <h5 class="text-uppercase text-center text-secondary">0 Produits</h5>
                  </td>
               </tr>
               <template v-for="(inv,j) in searchIventories" v-if="inv.QTE.length != 0">
                  <template>{{ initIndex(0) }}</template>
                  <tr v-for="(v,k) in inv.QTE_TOTAL" v-if="v>0">
                     <template v-if="index === 0">
                        <td class="align-middle" :rowspan="Object.keys(inv.QTE).length">{{ inv.reference }}</td>
                        <td class="align-middle" :rowspan="Object.keys(inv.QTE).length">{{ inv.name }}</td>
                     </template>
                     <td>{{ v }} {{ showUnit({name: inv.unity}) }}</td>
                     <td>{{ k }} {{ showUnit({name: inv.unity}, false) }}</td>
                     <td class="font-weight-bolder align-middle">{{ k * v }}
                        {{ showUnit({name: inv.unity}, false) }}(s)
                     </td>
                     <template v-if="index === 0">
                        <td :rowspan="Object.keys(inv.QTE).length" v-if="roles.includes('ADMINE')" class="align-middle">
                           {{ inv.ht | fixed_two }} <small>DH</small></td>
                        <td :rowspan="Object.keys(inv.QTE).length" class="align-middle font-weight-bolder">
                           {{ inv.price | fixed_two }}<small>DH</small>
                        </td>
                        {{ initIndex(1) }}
                     </template>
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
                  <label for="">Produit</label>
                  <input type="text" class="form-control rounded-pill" placeholder="Désignation ..."
                         @input="getInventoriesByRef" v-model="reference">
               </div>
               <hr>
               <button @click="print"
                       class="btn btn-primary text-uppercase text-white font-weight-bolder btn-block ">
                  Imprimer
               </button>
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
   name: "Inventories",
   components: {BigTitle, HalfCircleSpinner},
   data() {
      return {
         reference: '',
         isLoading: false,
         inventories: [],
         searchIventories: [],
         roles: store.getters.roles,
         index: 0
      }
   },
   mounted() {
      axios.get('/api/inventories/detail')
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

         axios.get(`/api/inventories/search/${ref}`)
            .then(({data}) => {
               this.searchIventories = data;
               this.isLoading = false
            })
            .catch(error => console.log(error.response))
      },
      print() {
         this.$htmlToPaper('printMe');
      },
      initIndex(inc = 0) {
         this.index = inc;
      }
   },
   watch: {
      reference: function (ref) {
         this.isLoading = true
      }
   }
}
</script>

<style lang="scss" scoped>
$orange: #ff9f42;

.segment {
   margin: 0;
   border-bottom: 2px solid $orange;
}
</style>
