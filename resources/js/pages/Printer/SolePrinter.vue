<template>
   <div class="">
      <h4 class="text-primary text-center text-uppercase my-4">Page D'impression </h4>
      <hr>
      <div id="printcmd">
         <div class="container mt-5">
            <table class="table table-bordered  table-sm table-striped">
               <thead>
               <tr class="p-3 text-center table-borderless">
                  <th colspan="2" class="border-warning">
                     {{order.client | getByChoice('nom')}} ({{order.client | getByChoice('id') }})
                  </th>
                  <th colspan="2" class="border-warning">
                     BON DE LIVRAISON N:
                     {{order.cmd_number}}
                  </th>
                  <th colspan="2" class="border-warning">Le: {{order.date_cmd | humane_date}}</th>
               </tr>
               <tr class="text-center">
                  <th class="font-weight-bolder border-dark text-uppercase">Produit</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Couleur</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Quantité</th>
                  <th class="font-weight-bolder border-dark text-uppercase">prix</th>
<!--                  <th class="font-weight-bolder border-dark text-uppercase">Remise</th>-->
                  <th class="font-weight-bolder border-dark text-uppercase" colspan="2">Montant</th>
               </tr>
               </thead>
               <tbody>
               <tr v-if="products.length === 0">
                  <td colspan="6">
                     <h4 class="text-secondary text-center">Y a pas de produit !</h4>
                  </td>
               </tr>
               <tr v-for="(p) in Object.keys(products)" class="text-center  ">
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].name}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].color}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].qte}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].price - products[p][0].discount}}
                  </td>
                  <td class="p-2 border-dark " colspan="2">
                     {{products[p][0].qte * (products[p][0].price-products[p][0].discount )}} DH
                  </td>
               </tr>
               <tr v-if=" Object.keys(products).length < 10">
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
               </tr>
               <tr>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark font-weight-bold ">TOTAL</td>
                  <td class="p-3 border-dark font-weight-bolder">{{count}} DH</td>
               </tr>
               </tbody>
               <tfoot>
               <tr class="text-center bg-white border-dark">
                  <td class="font-italic border-dark">Client</td>
                  <td class="font-italic border-dark" colspan="2">Magasinier</td>
                  <td class="font-italic border-dark">Sécurité</td>
                  <td class="font-italic border-dark" colspan="2">Controle</td>
               </tr>
               <tr>
                  <td class="p-5 border-dark"></td>
                  <td class="p-5 border-dark" colspan="2"></td>
                  <td class="p-5 border-dark"></td>
                  <td class="p-5 border-dark" colspan="2"></td>
               </tr>

               </tfoot>
            </table>
         </div>
      </div>
      <button @click="printcmd"
              class="btn btn-primary text-uppercase text-white font-weight-bolder float-right btn-block ">Imprimer Bon Livraison
      </button>
      <div id="printA">
         <div class="container mt-5">
            <table class="table table-bordered  table-sm table-striped">
               <thead>
               <tr class="p-3 text-center table-borderless">
                  <th colspan="2" class="border-warning">
                     {{order.client | getByChoice('nom')}} ({{order.client | getByChoice('id') }})
                  </th>
                  <th colspan="2" class="border-warning">
                     BON DE COMMANDE N:
                     {{order.cmd_number}} (A)
                  </th>
                  <th colspan="2" class="border-warning">Le: {{order.date_cmd | humane_date}}</th>
               </tr>
               <tr class="text-center">
                  <th class="font-weight-bolder border-dark text-uppercase">Produit</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Couleur</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Quantité</th>
                  <th class="font-weight-bolder border-dark text-uppercase">prix</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Remise</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Montant</th>
               </tr>
               </thead>
               <tbody>
               <tr v-if="products.length === 0">
                  <td colspan="6">
                     <h4 class="text-secondary text-center">Y a pas de produit !</h4>
                  </td>
               </tr>
               <tr v-for="(p) in Object.keys(products)" class="text-center  ">
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].name}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].color}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].qte}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].price}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].discount}}
                  </td>
                  <td class="p-2 border-dark ">
                     {{products[p][0].qte * (products[p][0].price-products[p][0].discount )}} DH
                  </td>
               </tr>
               <tr v-if=" Object.keys(products).length < 10">
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
               </tr>
               <tr>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark font-weight-bold ">TOTAL</td>
                  <td class="p-3 border-dark font-weight-bolder">{{count}} DH</td>
               </tr>
               </tbody>
               <tfoot>
               <tr class="text-center bg-white border-dark">
                  <td class="font-italic border-dark">Client</td>
                  <td class="font-italic border-dark" colspan="2">Magasinier</td>
                  <td class="font-italic border-dark">Sécurité</td>
                  <td class="font-italic border-dark" colspan="2">Controle</td>
               </tr>
               <tr>
                  <td class="p-5 border-dark"></td>
                  <td class="p-5 border-dark" colspan="2"></td>
                  <td class="p-5 border-dark"></td>
                  <td class="p-5 border-dark" colspan="2"></td>
               </tr>

               </tfoot>
            </table>
         </div>
      </div>
      <button @click="printA"
              class="btn btn-primary text-uppercase text-white font-weight-bolder float-right btn-block ">Imprimer
         BL (A)
      </button>
      <div id="printB">
         <div class="container mt-5">
            <table class="table table-bordered  table-sm table-striped">
               <thead>
               <tr class="p-3 text-center table-borderless">
                  <th colspan="2" class="border-warning">
                     {{order.client | getByChoice('nom')}} ({{order.client | getByChoice('id') }})
                  </th>
                  <th colspan="2" class="border-warning">
                     BON DE COMMANDE N:
                     {{order.cmd_number}} (B)
                  </th>
                  <th colspan="2" class="border-warning">Le: {{order.date_cmd | humane_date}}</th>
               </tr>
               <tr class="text-center">
                  <th class="font-weight-bolder border-dark text-uppercase">Produit</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Couleur</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Quantité</th>
                  <th class="font-weight-bolder border-dark text-uppercase">prix</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Remise</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Montant</th>
               </tr>
               </thead>
               <tbody>
               <tr v-if="products.length === 0">
                  <td colspan="6">
                     <h4 class="text-secondary text-center">Y a pas de produit !</h4>
                  </td>
               </tr>
               <tr v-for="(p) in Object.keys(products)" class="text-center  ">
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].name}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].color}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].qte}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].price}}
                  </td>
                  <td class="p-3 border-dark p-2">
                     {{products[p][0].discount}}
                  </td>
                  <td class="p-2 border-dark ">
                     {{products[p][0].qte * (products[p][0].price-products[p][0].discount )}} DH
                  </td>
               </tr>
               <tr v-if=" Object.keys(products).length < 10">
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
               </tr>
               <tr>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark"></td>
                  <td class="p-3 border-dark font-weight-bold ">TOTAL</td>
                  <td class="p-3 border-dark font-weight-bolder">{{count}} DH</td>
               </tr>
               </tbody>
               <tfoot>
               <tr class="text-center bg-white border-dark">
                  <td class="font-italic border-dark">Client</td>
                  <td class="font-italic border-dark" colspan="2">Magasinier</td>
                  <td class="font-italic border-dark">Sécurité</td>
                  <td class="font-italic border-dark" colspan="2">Controle</td>
               </tr>
               <tr>
                  <td class="p-5 border-dark"></td>
                  <td class="p-5 border-dark" colspan="2"></td>
                  <td class="p-5 border-dark"></td>
                  <td class="p-5 border-dark" colspan="2"></td>
               </tr>

               </tfoot>
            </table>
         </div>
      </div>
      <button @click="printB"
              class="btn btn-primary text-uppercase text-white font-weight-bolder float-right btn-block ">Imprimer
         BL (B)
      </button>

   </div>
</template>

<script>
   export default {
      name: "SolePrinter",
      data() {
         return {
            output: null,
            order: [],
            products: [],
            client: {nom: '', id: null},
            color: {name: ''},
            count: 0,
            product_size: []
         }
      },
      mounted() {
         this.getOrder()
      },
      methods: {
         getOrder() {
            const id = this.$route.params.id;
            axios.get(`/api/orders/${id}/print`)
               .then(({data}) => {
                  const {products, order} = data
                  this.products = products;

                  _.forEach(Object.keys(products), (p) => {
                     this.count += products[p][0].qte * (products[p][0].price - products[p][0].discount)
                  })

                  this.order = order;

               })
               .catch(err => {
                  console.log(err.response)
                  // this.$router.push("/404")
                  this.$notification.error("cette commande n'existe pas !")
               })
         },
         sumOfArticle(p) {
            return _.cloneDeep((p.price - p.discount) * p.qte);
         },
         printA() {
            this.$htmlToPaper('printA');
         }, printB() {
            this.$htmlToPaper('printB');
         },
         printcmd() {
            this.$htmlToPaper('printcmd');
         },
      }
   }
</script>

<style scoped>

</style>
