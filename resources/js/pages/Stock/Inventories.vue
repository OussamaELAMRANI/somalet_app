<template>
   <div id="inventories" class="">

      <big-title title="Stock des produits" position="text-left" classes="text-primary"/>
      <div class="row">
         <div class="col-9" id="printMe">
            <table class="table table-hover table-striped text-center">
               <thead class="bg-primary text-white text-uppercase small table-bordered">
               <tr>
                  <th>Reference</th>
                  <th>Designation</th>
                  <th style="width: 200px">Quantité</th>
                  <th>Unité</th>
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
               <tr v-for="(inv , k) in searchIventories" v-if="inv.QTE.length != 0">
                  <template>
                     <td class="align-middle">{{inv.reference}}</td>
                     <td class="align-middle">{{inv.name}}</td>
                     <!--                     <td>{{inv.QTE.length}}</td>-->
                     <td>
                        <ul class="list-group  list-group-horizontal" v-for="(v,k) in inv.QTE_TOTAL" v-if="v>0">

                           <li class="list-group-item  m-1 p-0 w-50 ">{{v}}</li>
                           <li class="list-group-item  my-1 p-0 bg-success"></li>
                           <li class="list-group-item  m-1 p-0 w-50 bg-success text-white">{{k}}</li>
                           <li class="list-group-item  my-1 p-0 bg-warning"></li>
                           <li class="list-group-item  my-1 p-0 bg-warning"></li>
                           <li class="list-group-item  m-1 p-0 w-50 bg-primary text-white">{{k*v}}</li>
                        </ul>
                     </td>
                     <td class="text-uppercase text-primary font-weight-light align-middle">{{inv.unity}}</td>
                     <td v-if="roles.includes('ADMINE')" class="align-middle">{{inv.ht | fixed_two}} <span
                        class="badge">DH</span></td>
                     <td class="align-middle">{{inv.price | fixed_two}} <span class="badge">DH</span></td>
                  </template>
               </tr>
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
               <!--               <div class="form-group">-->
               <!--                  <label for="">Prix</label>-->
               <!--                  <input type="text" class="form-control rounded-pill" placeholder="Prix de vente ...">-->
               <!--               </div>-->
               <hr>
               <!--               <button class="btn btn-outline-primary btn-block rounded-pill">Filter</button>-->
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
            roles: store.getters.roles
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
