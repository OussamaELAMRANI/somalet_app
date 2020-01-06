<template>
   <div id="fabric-reception">
      <h4 class="text-primary">
         Receptions de Semelles
      </h4>
      <hr>
      <div class="row">
         <div class="col-10">
            <table class="table-striped table table-bordered">
               <thead class="bg-dark text-light">
               <tr>
                  <th>#</th>
                  <th>Date de commande</th>
                  <th>Produit</th>
                  <th>Pointure</th>
                  <th>Qte fabriqué</th>
                  <th>Qte Commandé</th>
                  <th>Qte Rester</th>
               </tr>
               </thead>
               <tbody>
               <template v-for="(r,i) in receptions">
                  <tr v-for="(s,j) in r.product_sizes">
                     <template v-if="j%r.product_sizes.length === 0">
                        <td class="align-middle" :rowspan="r.product_sizes.length">{{i+1}}</td>
                        <td class="align-middle" :rowspan="r.product_sizes.length"> {{r.date_cmd | humane_date}}</td>
                     </template>

                     <td class="align-middle">{{s.product | getProductName}}</td>
                     <td class="align-middle">{{s.size | getProductSize}}</td>
                     <td class="align-middle">{{s.pivot | getQteFabric}}</td>
                     <td class="align-middle">{{s.pivot | getOrderQte}}</td>
                     <td class="align-middle">
                        <input type="number" class="form-control" placeholder="Reste ...">
                     </td>
                  </tr>


               </template>

               </tbody>
            </table>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "SolesReceptions",
      data() {
         return {
            receptions: []
         }
      },
      mounted() {
         axios.get(`/api/receptions/production`)
            .then(({data}) => {
               this.receptions = data;
            })
            .catch(err => console.log(err.response))
      },
      filters: {
         getProductName: function (p) {
            return (p == null) ? null : p.name
         },
         getProductSize: function (p) {
            return (p == null) ? null : p.size
         },
         getQteFabric: function (p) {
            return (p == null) ? null : p.fabric_quantity
         },
         getOrderQte: function (p) {
            return (p == null) ? null : p.order_quantity
         },

      }
   }
</script>

<style scoped>

</style>
