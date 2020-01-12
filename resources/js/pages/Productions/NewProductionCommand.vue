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
         <div class="col-7">
            <hr>
         </div>
         <div class="col-5" v-if="client_id">
            <select-product @sendProduct="getProduct" :client_id="client_id"/>
         </div>
      </div>

      <div class="row" v-if="client_id">
         <div class="col-7">
            <table class="table table-bordered text-center table-striped">
               <thead class="bg-dark text-light">
               <tr>
                  <th>Produit</th>
                  <th>Prix de Vente</th>
                  <th>Remise</th>
                  <th>Pointures</th>
                  <th>Quantites</th>
                  <th>Mantant (DH)</th>
                  <th>Actions</th>
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
                           <h6>
                              {{p.sell}} DH
                           </h6>
                        </td>
                        <td class="align-middle" :rowspan="p.qte.length">
                           <h6>
                              {{p.discount}} DH
                           </h6>
                        </td>
                     </template>
                     <td>{{q.size}}</td>
                     <td>
                        {{q.qte}}
                        <span class="badge">PAIRE</span>
                     </td>
                     <td>
                        {{(p.sell - p.discount) * q.qte}}
                     </td>
                     <template v-if="j % p.qte.length ===0">
                        <td class="align-middle" :rowspan="p.qte.length">
                           <button class="btn btn-danger" @click="deleteItem(p.id)">
                              <i class="fa fa-trash"></i>
                           </button>
                        </td>
                     </template>
                  </tr>
               </template>
               </tbody>
               <tfoot>
               <tr>
                  <td colspan="7">
                     <button @click="addCommand" class="btn btn-success btn-block">Enregister</button>
                  </td>
               </tr>
               </tfoot>
            </table>
         </div>
         <!-- ORDER -->
         <div class="col-5 segment">
            <ValidationObserver v-slot="{ handleSubmit }">
               <table class="table table-bordered text-center table-striped">
                  <thead class="bg-primary text-light">
                  <tr>
                     <th>Produit</th>
                     <th>Prix de Vente</th>
                     <th>Remise</th>
                     <th>Pointures</th>
                     <th>Stock</th>
                     <th>Quantites</th>
                  </tr>
                  </thead>
                  <tbody>
                  <template v-if="product !== null">
                     <tr v-for="(q,j) in product.qte">
                        <template v-if="j % product.qte.length === 0">
                           <td class="align-middle" :rowspan="product.qte.length">
                              <h5 class="text-uppercase"> {{product.name}}</h5>
                           </td>
                           <td class="align-middle" :rowspan="product.qte.length">
                              <h5>
                                 {{product.sell}} DH
                              </h5>
                           </td>
                           <td class="align-middle" :rowspan="product.qte.length">
                              <h5>
                                 {{product.discount}} DH
                              </h5>
                           </td>
                        </template>
                        <td>{{q.size}}</td>
                        <td>
                           {{q.order_quantity}}
                           <span class="badge">PAIRE</span>
                        </td>
                        <td>
                           <ValidationProvider v-slot="{errors}" :rules="'positive|max:'+getRest(q.order_quantity)">
                              <input type="number" class="form-control" v-model.number="order.qte[j].qte"/>
                              <span class="text-danger"> {{ errors[0]}}</span>
                           </ValidationProvider>
                        </td>
                     </tr>

                  </template>
                  </tbody>
                  <tfoot v-if="Object.keys(order).length !== 0">
                  <tr>
                     <td colspan="6" class="text-right">
                        <button @click="handleSubmit(addToList)" class="btn btn-success">
                           <i class="fa fa-plus"></i>
                           Inserer cette commande
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
   import SelectClient from "@/components/Client/SelectClient";
   import SelectDate from "@/components/layouts/SelectDate";
   import SelectProduct from "@/components/layouts/SelectProduct";
   import {extend, ValidationObserver} from 'vee-validate';

   export default {
      name: "NewProductionCommand",
      components: {SelectProduct, SelectDate, SelectClient, ValidationObserver},
      data() {
         return {
            client_id: 0,
            date_cmd: '',
            cmd_number: 0,
            product: {
               qte: []
            },
            order: {},
            orderList: [],
         }
      },
      mounted() {
         this.getOrderNumber()
         this.initValidation()
      },
      methods: {
         getClient(c) {
            this.client_id = c;
            this.order = this.product = {};
            this.orderList = [];
         },
         getDate(c) {
            this.date_cmd = c;
         },
         getProduct(p) {
            if (p !== null) {
               const all = [];
               _.forEach(p.qte, (q) => {
                  all.push({...q, qte: 0})
               });
               p.qte = all;
               this.product = p;
            }
         },
         getOrderNumber() {
            axios.get('/api/orders/cmd_number')
               .then(({data}) => {
                  const {cmd_number} = data;
                  this.cmd_number = Number(cmd_number) + 1
               })
         },
         addToList() {
            let {qte, id} = this.order
            this.deleteItem(id);

            qte = _.filter(qte, (q) => {
               return (q.qte > 0)
            });
            this.orderList.push(_.cloneDeep({...this.order, qte}))

            this.order = this.product = {}
         },
         deleteItem(id) {
            this.orderList = this.orderList.filter(i => {
               return (id !== i.id)
            })
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
         getRest(q) {
            return _.cloneDeep(q);
         },
         addCommand() {
            const order = {
               cmd_number: this.cmd_number,
               date_cmd: this.date_cmd,
               client_id: this.client_id,
            };
            const order_ps = this.orderList;

            axios.post('/api/orders/productions', {
               order,
               order_ps
            }).then(({data}) => {
               console.log(data)
               this.$router.push({name: 'listOrder'})
            })
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
