<template>
   <div id="fabric-reception">
      <h4 class="text-primary">
         Receptions de Semelles
      </h4>
      <hr>
      <div class="row">
         <div class="col-10">
            <ValidationObserver v-slot="{ handleSubmit }">

               <table class="table-striped table table-bordered">
                  <thead class="bg-dark text-light">
                  <tr>
                     <th>Date de commande</th>
                     <th>Satus</th>
                     <th>Produit</th>
                     <th>Pointure</th>
                     <th>Qte fabriqué</th>
                     <th>Qte Commandé</th>
                     <th>Ajouter une quanité</th>
                     <th>Le Reste</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(s,j) in receptions.product_sizes">
                     <template v-if="j%receptions.product_sizes.length === 0">
                        <td class="align-middle" :rowspan="receptions.product_sizes.length">
                           {{receptions.date_cmd |humane_date}}
                        </td>
                     </template>

                     <td class="align-middle">
                        <div v-html="getStatus(s.pivot)"></div>
                     </td>
                     <td class="align-middle">{{s.product | getProductName}}</td>
                     <td class="align-middle">{{s.size | getProductSize}}</td>
                     <td class="align-middle">{{s.pivot | getQteFabric}}</td>
                     <td class="align-middle">{{s.pivot | getOrderQte}}</td>
                     <td class="align-middle" v-if="getRest(s.pivot) > 0">
                        <ValidationProvider v-slot="{errors}"
                                            :rules="'positive|max:'+getRest(s.pivot)">
                           <input type="number" class="form-control" placeholder="Production ..."
                                  v-model.number="product_sizes[j].pivot.fabric_quantity">
                           <span class="text-danger"> {{ errors[0]}}</span>
                        </ValidationProvider>
                     </td>
                     <td class="align-middle text-center text-success" v-else>
                        {{s.updated_at | humane_date}}
                     </td>
                     <td>
                        {{getRest(s.pivot)}}
                     </td>
                  </tr>

                  </tbody>
                  <tfoot v-if="['VUE','ATTENTE'].includes(receptions.state)">
                  <tr>
                     <td colspan="6"></td>
                     <td colspan="2">
                        <button class="btn btn-block btn-success" @click="handleSubmit(sendProduction)">Valider
                           Tout
                        </button>
                     </td>
                  </tr>
                  </tfoot>
               </table>
            </ValidationObserver>

         </div>
      </div>
   </div>
</template>

<script>
   import {extend, ValidationObserver} from 'vee-validate';

   export default {
      components: {ValidationObserver},
      name: "SolesReceptions",
      data() {
         return {
            receptions: [],
            product_sizes: [],
         }
      },
      mounted() {
         this.initOrder();
         this.initValidation()
      },
      methods: {
         initOrder() {
            const {id} = this.$route.params;
            axios.get(`/api/receptions/${id}/detail`)
               .then(({data}) => {
                  let {product_sizes} = data;
                  this.initQte(_.cloneDeep(product_sizes));
                  this.receptions = data;
               })
               .catch(err => {
                  console.log(err.response);
                  this.$router.push('/404');
               })
         },
         getRest(p) {
            return p.order_quantity - p.fabric_quantity
         },
         getStatus(p) {
            const total = (p.order_quantity - p.fabric_quantity);
            if (total === 0)
               return `<p class="badge badge-success">TERMINER</p>`;
            else if (total === p.order_quantity)
               return `<p class="badge badge-danger">EN ATTENT</p>`;
            else
               return `<p class="badge badge-warning">EN COURS</p>`;

         },
         sendProduction() {
            const {id} = this.$route.params;
            axios.put(`/api/receptions/${id}/validate-reception`,
               {products: this.product_sizes}
            ).then(({data}) => {
               // console.log(data)
               this.$router.push({name: 'listOrderReception'})
            }).catch(err => {
               console.log(err.response);
            })
         },
         initQte(s) {
            _.forEach(s, (p) => {
               p['pivot']['fabric_quantity'] = 0
            })
            this.product_sizes = s;
         },
         initValidation() {
            extend('positive', value => {
               if (value >= 0)
                  return true;
               return "Ne peut être négative !"
            });
            extend('max', {
               validate(value, args) {
                  return value <= args.max;
               },
               params: ['max'],
               message: `Quantité max est {max}`
            });
         },

      }
   }
</script>

<style scoped>

</style>
