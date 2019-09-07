<template>
   <div id="order_table" class="my-5">
      <table class="table">
         <thead class="thead-dark">
         <tr>
            <th>Reference</th>
            <th>Designation</th>
            <th>Quantité</th>
            <th>Remise</th>
            <th>Prix</th>
            <th>Prix de vente</th>
            <th>Montant</th>
            <th>Action</th>
         </tr>
         </thead>
         <tbody>
         <tr v-for="(order, i) in orders">
            <td>{{order.reference}}</td>
            <td>{{order.name}}</td>
            <td>{{order.qte}}</td>
            <td>{{order.discount}}</td>
            <td>{{order.prix}}</td>
            <td>{{order.prix - order.discount | twoFixed}}</td>
            <td>{{(order.prix - order.discount)*order.qte*order.qte_rapport | twoFixed}}</td>
            <td>
               <button class="btn btn-danger btn-sm rounded-circle">
                  <i class="fa fa-minus"></i>
               </button>
            </td>
         </tr>
         </tbody>
         <tfoot class="segment">
         <tr>
            <td colspan="6" class="">
               <span class="badge font-weight-bolder badge-success badge-pill">{{orders.length}} Produit (s)</span>
            </td>
            <td class="text-primary font-weight-bolder">{{total | twoFixed}} DH</td>
            <td colspan="5" class="text-primary font-weight-bolder"> TOTAL</td>
         </tr>
         </tfoot>
      </table>
   </div>
</template>

<script>
   export default {
      name: "OrderTable",
      props: {
         orders: {}
      },
      data() {
         return {
            all: [],
            total: 0
         }
      },
      computed: {
         getAll() {
            const commands = _.cloneDeep(this.orders)
            this.all.push(...commands)

            return this.all
         },

      },
      watch: {
         orders: {
            // deep: true,
            handler(arr) {
               this.total = 0
               _.forEach(arr, (p) => {
                  // console.log(p)
                  this.total += Number((p.prix - p.discount) * p.qte)
               })
            }
         },
      },
      filters: {
         twoFixed: function (value) {
            if (!value) return ''
            value = Number(value)
            return value.toFixed(2)
         }
      }
   }
</script>

<style scoped>

</style>
