<template>
   <div id="inventories" class="">

      <big-title title="Stock des produits" position="text-left" classes="text-primary"/>
      <div class="row">
         <div class="col-9">
            <table class="table table-hover table-striped text-center">
               <thead class="bg-primary text-white text-uppercase small table-bordered">
               <tr>
                  <th>Reference</th>
                  <th>Designation</th>
                  <th>Quantité</th>
                  <th>Unité</th>
                  <th>CR HT</th>
                  <th>Prix de Vente</th>
               </tr>
               </thead>
               <tbody class="segment">
               <tr v-for="(inv , k) in inventories" v-if="inv.QTE.length != 0">
                  <template>
                     <td class="align-middle">{{inv.reference}}</td>
                     <td class="align-middle">{{inv.name}}</td>
<!--                     <td>{{inv.QTE.length}}</td>-->
                     <td>
                        <ul class="list-group  list-group-horizontal" v-for="(v,k) in inv.QTE_TOTAL">
                           <li class="list-group-item  m-1 p-0 w-50 ">{{v}}</li>
                           <li class="list-group-item  my-1 p-0 bg-warning"></li>
                           <li class="list-group-item  m-1 p-0 w-50 bg-success text-white">{{k}}</li>
                        </ul>
                     </td>
                     <td class="text-uppercase text-primary font-weight-light align-middle">{{inv.unity}}</td>
                     <td class="align-middle">{{inv.ht | fixed_two}} <span class="badge">DH</span></td>
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
                  <input type="text" class="form-control rounded-pill" placeholder="Désignation ...">
               </div>
               <div class="form-group">
                  <label for="">Prix</label>
                  <input type="text" class="form-control rounded-pill" placeholder="Prix de vente ...">
               </div>
               <hr>
               <button class="btn btn-outline-primary btn-block rounded-pill">Filter</button>
            </div>
         </div>

      </div>
   </div>
</template>

<script>
    import BigTitle from "@/components/layouts/BigTitle";

    export default {
        name: "Inventories",
        components: {BigTitle},
        data() {
            return {
                inventories: []
            }
        },
        mounted() {
            axios.get('/api/inventories/detail')
                .then(({data}) => {
                    this.inventories = data
                })
        }
    }
</script>

<style lang="scss" scoped>
   $purple: #9561E2;
   .segment {
      margin: 0;
      border-bottom: 2px solid $purple;
   }
</style>
