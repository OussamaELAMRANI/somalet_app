<template lang="pug">
   #update_order.my-4.clearfix
      big-title(title="Modifier une Commande" position="text-center" :classes="['h3','text-secondary','text-uppercase','my-5']")
      .row
         .col-8
            .row
               .col-12
                  form.row
                     .col
                        .form-group
                           label Client
                           input.form-control(type="text" :value="order.client | getByChoice('nom')" disabled)
                     .col
                        select-date(@sendDate="getDate" title="Date de la Facture :" :currentDate="order.date_cmd")
                     .col
                        .form-group
                           label(for="delivery") Numéro de Livraison
                           input.form-control(type='text', placeholder='Numero de Facture ...' id='delivery' v-model='order.cmd_number' disabled)
                  hr
               .col-12
                  table.table.table-hover.table-striped.text-center
                     thead.bg-primary.text-white
                        tr
                           th Reference
                           th Designation
                           th Quantit&eacute;
                           th Remise
                           th Prix
                           th Prix de vente
                           th Action
                     tbody
                        tr.segment(v-if="oldProducts.length === 0 && updatedProducts.length === 0")
                           td(colspan='7')
                              h4.text-center.text-uppercase.font-italic  Pas de produit !
                        template(v-else)
                           tr.old-products( v-for="(order, i) in oldProducts" :key="'old_'+i")
                              td {{order.reference}}
                              td {{order.name}}
                              td
                                 ul.list-group.list-group-horizontal
                                    li.list-group-item.m-1.p-0.w-50 {{order.pivot | getByChoice('qte')}}
                                    li.list-group-item.my-1.p-0.bg-success
                                    li.list-group-item.m-1.p-0.w-50.bg-success.text-white {{order.pivot | getByChoice('qte_rapport')}}
                                    li.list-group-item.my-1.p-0.bg-warning
                                    li.list-group-item.my-1.p-0.bg-warning
                                    li.list-group-item.m-1.p-0.w-50.bg-primary.text-white {{calcTotal(order.pivot.qte, order.pivot.qte_rapport)}}
                              td {{order.pivot | getByChoice('discount')}}
                              td {{order.pivot | getByChoice('price')}} DH
                              td {{calcTotal(calcTotal(order.pivot.qte, order.pivot.qte_rapport),subtraction(order.pivot.price, order.pivot.discount))  | fixed_two}} DH
                              td
                                 button.btn.btn-outline-danger.btn-sm.rounded-pill.shadow(@click="deleteItem(order.pivot)")
                                    i.fa.fa-minus

                           template(v-for="(order, i) in updatedProducts")
                              tr.new-products(v-for="(v,k) in order.qte_cmd")
                                 td {{order.reference}}
                                 td {{order.name}}
                                 td
                                    ul.list-group.list-group-horizontal
                                       li.list-group-item.m-1.p-0.w-50 {{v}}
                                       li.list-group-item.my-1.p-0.bg-success
                                       li.list-group-item.m-1.p-0.w-50.bg-success.text-white {{k}}
                                       li.list-group-item.my-1.p-0.bg-warning
                                       li.list-group-item.my-1.p-0.bg-warning
                                       li.list-group-item.m-1.p-0.w-50.bg-primary.text-white {{k*v}}
                                 td {{order.discount}}
                                 td {{order.price}}
                                 td {{(order.price - order.discount) * (v * k) | fixed_two}}
                                 td
                                    button.btn.btn-outline-danger.btn-sm.rounded-pill.shadow(@click="destroyItem(order.product_id)")
                                       i.fa.fa-minus
               .col-12
                  button(@click="updateOrder" class='btn btn-primary text-white float-right' v-if="updatedProducts.length !== 0 || trash.length !== 0") Modifier cette commande
                  button.btn.btn-outline-secondary.float-right(@click="$router.back()") Annuler

         .col-4
            form(@submit.prevent="" v-if="order.client_id != null")
               .row.justify-content-center.my-4.segment.shadow
                  .col-12
                     h4.text-primary.text-capitalize Selectionnez produit
                  .col-12
                     .form-group
                        label Rechercher par désignation de produit
                        cool-select(:items='prods' @search='onSearch' :loading='loading'
                           item-text='name' name='produit' v-model='putOrder')
                           //item-value='id'
                           template(slot='item' slot-scope='{ item:p }')
                              .d-flex
                                 div
                                    p {{ p.name }} -
                                       span.text-primary.font-weight-bolder {{ p.reference }}
                           template(slot='no-data') {{ noData ? "Aucun produit trouvée" :"Chercher par nom de produit" }}
                     hr
                  //transition(name="expand" @enter="enter" @after-enter="afterEnter" @leave="leave")
                  .row(v-if="putOrder !== null")
                     .col-12
                        table-layout(:head-table="['Designation','Prix','Remise']", theme="text-white bg-info small", data="selected_order")
                           tr.segment
                              td {{(selected_order) == null ? null : selected_order.name}}
                              td {{(selected_order) == null ? null : selected_order.price | fixed_two}}
                              td {{(selected_order) == null ? null : selected_order.discount}}
                     .col-12
                        p.lead Quantité en détail
                        table.table.table-hover.table-striped.text-center
                           thead.bg-primary.text-white.small
                              tr
                                 th Qunatité
                                 th Rapport
                                 th Ajouter
                           tbody.segment(v-if="selected_order.QTE_TOTAL !== null")
                              tr(v-for="(v,k) in selected_order.QTE_TOTAL" )
                                 td.align-middle {{v}}
                                 td.align-middle {{k}}
                                 td
                                    input.form-control(type='number' placeholder='Quantite ...' aria-label='' v-model.number='qte[k]')
                     .col-12(v-if="qteError")
                        hr
                        p.text-danger.mb-0.text-uppercase Erreur des quantités !
                        p.text-info SVP changer les quantites pour la disponiblité du stock

                     .col-12(v-if="hasError")
                        hr
                        p.text-danger.mb-0.text-uppercase Erreur d'insertion !
                        p.text-info SVP inserer au moins une quntité au dessus
                     .col-12
                        hr
                        button.btn.btn-block.btn-outline-primary.rounded-pill(@click="getOrders" ) {{'Inserer '}}

</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import OrderTable from "@/components/Orders/OrderTable";
   import {CoolSelect} from 'vue-cool-select'
   import moment from 'moment'
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import TableLayout from "@/components/layouts/TableLayout";
   import SelectDate from "@/components/layouts/SelectDate";
   import {mapActions} from 'vuex'

   export default {
      name: "UpdateOrder",
      components: {SelectDate, TableLayout, OrderTable, BigTitle, CoolSelect, Datepicker},
      mounted() {
         this.oldOrder();
         // this.getClients()
         // this.getOrderNumber()
      },
      data() {
         return {
            fr,
            dateCmd: moment().toDate(),
            // header_order: {
            //    client_id: '',
            //    cmd_number: null,
            //    date_cmd: moment().toDate(),
            // },
            order: {},
            qte: [],
            oldProducts: [],
            clients: [],
            loading: false,
            noData: false,
            prods: [],
            selected_order: {
               reference: "",
               name: "",
               price: "",
               discount: 0,
               QTE: {},
               QTE_TOTAL: {},
               isSelected: false
            },
            putOrder: null,
            qteError: false,
            hasError: false,
            trash: [],
            tmpProducts: [],
            updatedProducts: []
         }
      },
      methods: {
         oldOrder() {
            const {id} = this.$route.params;
            const router = this.$router;
            this.load({
               url: `/api/orders/${id}`,
               params: {
                  params: {
                     with: 'products,client'
                  }
               },
               router
            }).then(data => {
               this.order = data;
               this.oldProducts = this.tmpProducts = data.product
            })
         },
         getDate(d) {
            this.order.date_cmd = d
         },
         getOrders() {
            let hasError = false
            const qte = {'qte_cmd': {...this.qte}}
            const products = this.selected_order
            const total = {...products.QTE_TOTAL}

            if (Object.keys(qte.qte_cmd).length === 0) {
               this.hasError = true;
               return;
            } else if (Object.values(qte.qte_cmd)[0] == "") {
               this.hasError = true;
               return;
            } else {
               this.hasError = false
            }

            Object.keys(total).forEach(function (k) {
               if (qte.qte_cmd[k] !== undefined || qte.qte_cmd[k] !== "") {
                  if (total[k] < qte.qte_cmd[k])
                     hasError = true
               }

            });

            if (hasError) {
               this.qteError = true
               return
            } else
               this.qteError = false


            const command = {...products, ...qte}
            this.destroyItem(command.product_id)

            const _this = this;

            Object.keys(qte.qte_cmd).forEach(function (k) {
               _this.deleteItem({
                  product_id: command.product_id,
                  qte_rapport: Number(k)
               })

            });

            this.updatedProducts.unshift(command)

            this.qte = []
            this.putOrder = []

         },
         onSearch(search) {
            // let rejectId = ""
            const lettersLimit = 2;

            this.noData = false;
            if (search.length < lettersLimit) {
               this.prods = [];
               this.loading = false;
               return;
            }
            this.loading = true;

            axios.get(`/api/receptions/search/${search}`, {
               params: {
                  client_id: this.order.client_id,
               }
            }).then(({data}) => {
               this.prods = data;
               if (data.length === 0)
                  this.noData = true

               this.loading = false;
            })
         },
         updateOrder() {
            const {id} = this.$route.params
            const products = this.mergeProducts();
            if (!products) {
               this.$notification.error('Ils ne sont aucun produit dans cette commande!');
               return
            }

            // old head
            const date_cmd = this.order.date_cmd;
            products.push(..._.map(this.oldProducts, old => old.pivot))

            console.log(products)
            axios.put(`/api/orders/${id}/update`, {date_cmd, products,})

               .then(({data}) => {
                  console.log(data)
                  this.$router.push({name: 'listOrder'})
                  this.$notification.success("Cette commande a été bien modifié ! ")
               })
               .catch(err => {
                  console.log(err.response)
               })
         },
         deleteItem(item) {
            const {product_id, qte_rapport} = item;
            // Delete Old Product by ID
            this.oldProducts = _.filter(this.oldProducts, cmd => (product_id !== cmd.id)); // || cmd.pivot.qte_rapport !== qte_rapport

            // Check if we already had this product on the Trash
            const fullTrash = _.reject(this.trash, cmd => (product_id !== cmd.id)); // || cmd.pivot.qte_rapport !== qte_rapport
            if (this.isEmpty(fullTrash))
               this.trash.push(..._.reject(this.tmpProducts, cmd => (product_id !== cmd.id))) //|| cmd.pivot.qte_rapport !== qte_rapport

         }
         ,
         destroyItem(id) {
            this.updatedProducts = this.updatedProducts.filter(i => {
               return (id !== i.product_id)
            })
         }
         ,
         mergeProducts() {
            const oldest = this.oldProducts;
            const newest = this.updatedProducts;
            if (this.isEmpty(oldest) && this.isEmpty(newest)) {
               return false
            }

            const products = [];
            const updated = this.updatedProducts.map(p => {

               const qte = p.qte_cmd
               const prods = []
               // const qte_rapport = Object.keys(p.qte_cmd)

               Object.keys(qte).forEach(function (k) {
                  prods.push({
                     'product_id': p.product_id,
                     'price': Number(p.price),
                     'discount': Number(p.discount),
                     'qte': qte[k],
                     qte_rapport: Number(k)
                  })

               });

               return prods;
            });

            _.forEach(updated, up => {
               products.push(...up)
            });

            return products;
         }
         ,
         ...
            mapActions({load: 'updateStore/getData'}),
      },
      watch: {
         // dateCmd: function (d) {
         //    this.header_order.date_cmd = moment(d).format('DD/MM/YYYY');
         // },
         putOrder: function (o) {
            // console.log(o)
            if (o === null) return;

            const trash = this.trash;

            if (!this.isEmpty(trash)) {
               const hasProduct = _.filter(trash, t => t.id === o.product_id)
               if (!this.isEmpty(hasProduct)) {
                  _.forEach(hasProduct, p => {
                     if (!o['isSelected']) {
                        const {qte, qte_rapport} = p.pivot;
                        o.QTE_TOTAL[qte_rapport] += qte
                     }
                  });
                  o['isSelected'] = true
               }
            }
            this.qte = []; // To Avoid Putting Invalid data to Commands
            this.selected_order = {...o}
            console.log(o.isSelected)
         }
         ,
         trash: function () {
            this.putOrder = null
            this.selected_order = []
         }
         ,
         selected_order: function (s) {

         }
      }
      ,


   }
</script>

<style scoped>
   tr.old-products {
      border-left: 2px solid #F99B5B;
      border-right: 2px solid #F99B5B;
   }

   tr.new-products {
      border-left: 2px solid #31f93a;
      border-right: 2px solid #31f93a;
   }

</style>
