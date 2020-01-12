<template>
   <div id="new_command">
      <div class="row justify-content-center segment">
         <div class="col-3">
            <div class="form-group">
               <label for="delivery">Numéro de Livraison</label>
               <input class="form-control" type="text" id="delivery" v-model="cmd_number" disabled>
            </div>
         </div>
         <div class="col-3">
            <select-client @sendClient="getClient"/>
         </div>
         <div class="col-3">
            <select-date title="Date de commande" @sendDate="getDate"/>
         </div>
      </div>
      <div class="row align-items-center">
         <div class="col-8">
            <hr>
         </div>
         <div class="col-4">
            <select-product @sendProduct="getProduct"/>
         </div>
      </div>

      <div class="row">
         <div class="col-8">
            <table class="table table-bordered text-center table-striped">
               <thead class="bg-dark text-light">
               <tr>
                  <th>Produit</th>
                  <th>Prix de Vente</th>
                  <th>Pointures</th>
                  <th>Stock</th>
                  <th>Quantites</th>
               </tr>
               </thead>
               <tbody>
               <template v-for="p in orderList">

                  <tr v-for="(q,j) in p.qte">

                     <template v-if="j % p.qte.length ===0">
                        <td class="align-middle" :rowspan="p.qte.length">
                           <h5 class="text-uppercase"> {{p.name}}</h5>
                        </td>
                        <td class="align-middle" :rowspan="p.qte.length">
                           <h5>
                              {{p.sell}} DH
                           </h5>
                        </td>
                     </template>
                     <td>{{q.size}}</td>
                     <td>
                        {{q.order_quantity}}
                        <span class="badge">PAIRE</span>
                     </td>
                     <td>
                        {{q.qte}}
                     </td>
                  </tr>
               </template>
               </tbody>
            </table>
         </div>
         <div class="col-4 segment">
            <table class="table table-bordered text-center table-striped">
               <thead class="bg-dark text-light">
               <tr>
                  <th>Produit</th>
                  <th>Prix de Vente</th>
                  <th>Pointures</th>
                  <th>Stock</th>
                  <th>Quantites</th>
               </tr>
               </thead>
               <tbody>
               <template v-if="product !== null">
                  <tr v-for="(q,j) in product.qte">
                     <template v-if="j % product.qte.length ===0">
                        <td class="align-middle" :rowspan="product.qte.length">
                           <h5 class="text-uppercase"> {{product.name}}</h5>
                        </td>
                        <td class="align-middle" :rowspan="product.qte.length">
                           <h5>
                              {{product.sell}} DH
                           </h5>
                        </td>
                     </template>
                     <td>{{q.size}}</td>
                     <td>
                        {{q.order_quantity}}
                        <span class="badge">PAIRE</span>
                     </td>
                     <td>
                        <input type="number" class="form-control" v-model.number="order.qte[j].qte">
                     </td>
                  </tr>

               </template>
               </tbody>
               <tfoot>
               <tr>
                  <td colspan="5" class="text-right">
                     <button @click="addToList" class="btn btn-success">
                        <i class="fa fa-plus"></i>
                        Inserer cette commande
                     </button>
                  </td>
               </tr>
               </tfoot>
            </table>

         </div>

      </div>

   </div>
</template>

<script>
   import SelectClient from "@/components/Client/SelectClient";
   import SelectDate from "@/components/layouts/SelectDate";
   import SelectProduct from "@/components/layouts/SelectProduct";

   export default {
      name: "NewProductionCommand",
      components: {SelectProduct, SelectDate, SelectClient},
      data() {
         return {
            client_id: 0,
            date_cmd: '',
            cmd_number: 0,
            product: {
               qte: []
            },
            order: {
               qte: []
            },
            orderList: [],
         }
      },
      mounted() {
         this.getOrderNumber()
      },
      methods: {
         getClient(c) {
            this.client_id = c;
         },
         getDate(c) {
            this.date_cmd = c;
         },
         getProduct(p) {
            const qte = [];
            _.forEach(p.qte, (q) => {
               qte.push({...q, qte: 0})
            })
            p.qte = qte
            this.product = p;
         },
         getOrderNumber() {
            axios.get('/api/orders/cmd_number')
               .then(({data}) => {
                  const {cmd_number} = data;
                  this.cmd_number = Number(cmd_number) + 1
               })
         },
         addToList() {
            let {qte} = this.order
            qte = _.filter(qte, (q) => {
               return (q.qte > 0)
            });
            this.orderList.push(_.cloneDeep({...this.order, qte}))
            this.order = this.product = {}
         }
      },
      watch: {
         product: function (p) {
            this.order = _.cloneDeep(p);
         }
      }
   }
</script>

<style scoped>

</style>
