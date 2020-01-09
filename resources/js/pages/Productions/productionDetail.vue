<template>
   <div id="detail_production">
      <h4 class="text-uppercase">
         Detail
      </h4>
      <hr>
      <div class="row justify-content-center">
         <div class="col-10 my-3 text-right" v-if="receptions.state ==='RECEPTION'">
            <button class="btn btn-success" @click="ValidateOrder">VALIDER CETTE COMMANDE</button>
         </div>
         <div class="col-10">
            <table class="table-striped table table-bordered text-center">
               <thead class="bg-dark text-light">
               <tr>
                  <th>Date de commande</th>
                  <th>Produit</th>
                  <th>Pointure</th>
                  <th>Qte fabriqué</th>
                  <th>Qte Commandé</th>
                  <th>Status</th>
               </tr>
               </thead>
               <tbody>
               <tr v-for="(s,j) in receptions.product_sizes">
                  <template v-if="j%receptions.product_sizes.length === 0">
                     <td class="align-middle" :rowspan="receptions.product_sizes.length">
                        <h5>{{receptions.date_cmd | humane_date}}</h5>
                     </td>
                  </template>

                  <td class="align-middle">{{s.product | getProductName}}</td>
                  <td class="align-middle">{{s.size | getProductSize}}</td>
                  <td class="align-middle">{{s.pivot | getQteFabric}}</td>
                  <td class="align-middle">{{s.pivot | getOrderQte}}</td>
                  <td class="align-middle">
                     <div v-html="getStatus(s.pivot)"></div>
                  </td>
               </tr>

               </tbody>
            </table>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "productionDetail",
      data() {
         return {
            receptions: {}
         }
      },
      mounted() {
         const {id} = this.$route.params;

         axios.get(`/api/receptions/${id}/detail`)
            .then(({data}) => {
               this.receptions = data;
            })
            .catch(err => {
               console.log(err.response);
               this.$router.push('/404');
            })
      },
      methods: {
         getStatus(p) {
            const total = (p.order_quantity - p.fabric_quantity);
            if (total === 0)
               return `<p class="badge badge-success">TERMINER</p>`;
            else if (total === p.order_quantity)
               return `<p class="badge badge-danger">EN ATTENT</p>`;
            else
               return `<p class="badge badge-warning">EN COURS</p>`;

         },
         ValidateOrder(){
            const {id} = this.$route.params;
            axios.put(`/api/receptions/${id}/state`, {state: 'VALID'})
               .then(({data}) => {
                  this.$router.push({name: 'listOrderProduction'})
               }).catch(err => {
               console.log(err.response);
            })
         }
      }
   }

</script>

<style scoped>

</style>
