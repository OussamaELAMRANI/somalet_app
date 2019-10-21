<template>
   <div>
      <h4 class="text-primary text-center text-uppercase my-4">Page D'impression </h4>
      <hr>
      <!-- SOURCE -->
      <div id="printMe">
         <div class="container mt-5">
            <table class="table table-bordered  table-sm table-striped">
               <thead>
               <tr class="p-3 text-center table-borderless">
                  <th colspan="2" class="border-warning">
                     {{client.nom }} ({{client.id}})
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
                  <th class="font-weight-bolder border-dark text-uppercase">qunité</th>
                  <th class="font-weight-bolder border-dark text-uppercase">prix</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Remise</th>
                  <th class="font-weight-bolder border-dark text-uppercase">Montant</th>
               </tr>
               </thead>
               <tbody>
               <tr v-if="products.length == 0">
                  <td colspan="6">
                     <h4 class="text-secondary text-center">Y a pas de produit !</h4>
                  </td>
               </tr>
               <tr v-for="(p, i) in products" class="text-center  ">
                  <td class="p-2  border-dark text-uppercase">{{p.reference}}</td>
                  <td class="p-2 border-dark ">{{p.color.name}}</td>
                  <td class="p-2 border-dark ">{{p.pivot.qte_rapport * p.pivot.qte}}</td>
                  <td class="p-2 border-dark ">{{p.pivot.price}} DH</td>
                  <td class="p-2 border-dark ">{{p.pivot.discount}}</td>
                  <td class="p-2 border-dark ">{{(p.pivot.price - p.pivot.discount) * (p.pivot.qte_rapport * p.pivot.qte)}} DH</td>
               </tr>
               <tr v-if="products.length < 10">
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
                  <td class="p-3 border-dark font-weight-bolder">{{total}} DH</td>
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
      <!-- OUTPUT -->
      <button @click="print"
              class="btn btn-primary text-uppercase text-white font-weight-bolder float-right btn-block ">Imprimer
      </button>
   </div>
</template>

<script>
    export default {
        name: "Printer",
        props: {
            items: {}
        },
        data() {
            return {
                output: null,
                order: [],
                products: [],
                client: {nom: '', id: null},
                color: {name:''},
                total: 0
            }
        },
        mounted() {
            const {url} = this.$route.params;
            axios.get(url)
                .then(({data}) => {
                    const {client, product} = data
                    this.order = data
                    this.client = client
                    this.products = product

                    _.forEach(product, (p) => {
                        this.total += (p.pivot.price - p.pivot.discount) * (p.pivot.qte_rapport * p.pivot.qte)
                    })
                })
        },
        methods: {
            print() {
                // Pass the element id here
                this.$htmlToPaper('printMe');
            }
        }
    }
</script>

<style scoped>

</style>
