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
            <th>Action</th>
         </tr>
         </thead>
         <tbody>
         <tr v-if="orders.length === 0" class="segment">
            <td colspan="7">
               <h4 class="text-center text-uppercase font-italic"> Pas de produit !</h4>
            </td>
         </tr>
         <template v-for="(order, i) in orders" v-else>
            <tr v-for="(v,k) in order.qte_cmd ">
               <td>{{order.reference}}</td>
               <td>{{order.name}}</td>
               <td>{{v * k}}</td>
               <td>{{order.discount}}</td>
               <td>{{order.price}}</td>
               <td>{{(order.price - order.discount) * (v * k) | fixed_two}}</td>
               <td>
                  <button class="btn btn-danger btn-sm rounded-circle">
                     <i class="fa fa-minus"></i>
                  </button>
               </td>
            </tr>
         </template>

         </tbody>
      </table>
   </div>
</template>

<script>
   export default {
      name: "OrderTable",
      props: {
         orders: {
            default:{}
         }
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
            if (!value) return null
            value = Number(value)
            return value.toFixed(2)
         }
      }
   }
</script>

<style scoped>

</style>
