<template lang="pug">
   #new_order.my-4.clearfix
      big-title(title="Nouveau Commande" position="text-left" :classes="['h5','text-primary']")
      .container
         .row.justify-content-center
            .col-10
               form.row
                  .col-4
                     label Client
                     cool-select(:items='clients' :item-text="'nom'" item-value='id' name='client' placeholder="Nom ou Prénom" v-model='header_order.client_id'
                        v-validate="'required'")
                        template(slot='item' slot-scope='{ item:p }')
                           p {{ p.lastName }}
                              strong {{ ' '+ p.firstName }}
                     span.help.text-danger(v-show="errors.has('client')") Vous devez selectionner le client
                  .col-4
                     .form-group
                        label(for="delivery") Numéro de Livraison
                        input(class='form-control' type='text', placeholder='Numero de Facture ...' id='delivery' name="cmd_number"
                           v-validate="'required'" v-model='header_order.cmd_number')
                        span.help.text-danger(v-show="errors.has('cmd_number')") Vous devez Saisire le numéro de la livraison
                  .col-4
                     .form-group
                        label Date de la Facture :
                        datepicker.form-control(:format='customFormatter' :language='fr' :monday-first='true'
                           calendar-button-icon='fa fa-user' name='date_picker' v-model='dateCmd')
         .dropdown-divider.mb-5
         form(@submit.prevent="" v-if="header_order.client_id != null")
            .row.justify-content-center.my-4
               .col-8
                  label Rechercher par désignation de produit
                  cool-select(:items='prods' @search='onSearch' :loading='loading'
                     item-text='name' name='produit' v-model='putOrder' v-validate="'required'" )
                     //item-value='id'
                     template(slot='item' slot-scope='{ item:p }')
                        .d-flex
                           div
                              p {{ p.name }} - {{ p.reference }}
                     template(slot='no-data') {{ noData ? "Aucun produit trouvée" :"Chercher par nom de produit" }}
            .row.justify-content-center.my-4
               .col-12
                  table-layout(:head-table="['Reference','Designation','Prix','Remise']", theme="text-white bg-success", data="selected_order")
                     tr
                        td {{(selected_order) == null ? null : selected_order.reference}}
                        td {{(selected_order) == null ? null : selected_order.name}}
                        td {{(selected_order) == null ? null : selected_order.price | fixed_two}}
                        //td {{(selected_order) == null ? null : selected_order.ht | fixed_two}}
                        td {{(selected_order) == null ? null : selected_order.discount}}

                  .row.justify-content-center
                     .col-8
                        p.lead Quantité en détail
                        table.table.table-hover.table-striped.text-center()
                           thead.bg-success.text-white
                              tr
                                 th Qunatité
                                 th Rapport
                                 th
                                 th Ajouter
                           tbody.segment(v-if="selected_order.QTE_TOTAL !== null")
                              tr(v-for="(v,k) in selected_order.QTE_TOTAL" )
                                 td {{v}}
                                 td {{k}}
                                 td
                                 td
                                    input.form-control(type='number' placeholder='Quantite ...' aria-label='' v-model='qte[k]')
                           tfoot.segment
                              tr.text-right
                                 td(colspan="4")
                                    button.btn.btn-sm.btn-outline-primary(@click="getOrders" v-if="qte.length > 0") {{'Inserer '}}
                                       i.fa.fa-chevron-down

         hr
         order-table(:orders="commands")
         button(@click="addOrder" class='btn btn-success float-right') Ajouter la commande
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import OrderTable from "@/components/Orders/OrderTable";
   import {CoolSelect} from 'vue-cool-select'
   import moment from 'moment'
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "NewOrder",
      components: {TableLayout, OrderTable, BigTitle, CoolSelect, Datepicker},
      mounted() {
         axios.get('/api/clients')
            .then(({data}) => {
               this.clients = data
               console.log(data)
            })
            .catch(err => console.log(err.response))
      },
      data() {
         return {
            fr,
            dateCmd: moment().format('DD/MM/YYYY'),
            header_order: {
               client_id: '',
               cmd_number: '',
               date_cmd: moment().format('DD/MM/YYYY'),
            },
            order: {
               // product_id: '',
               // produit: '',
               qte: null,
            },
            qte: [],
            commands: [],
            clients: [],
            loading: false,
            noData: false,
            prods: Array(),
            selected_order: {
               reference: "",
               name: "",
               price: "",
               discount: "",
               QTE: {},
               QTE_TOTAL: {}
            },
            putOrder: {}
         }
      },
      methods: {
         getOrders() {
            // const orders = _.cloneDeep(this.order)
            const qte = {'qte_cmd': {...this.qte}}
            const products = this.putOrder
            const command = {...products, ...qte}
            console.log(command)
            // if (orders.product !== '' && orders.qte !== '')
            this.commands.unshift(command)
            this.qte = []
            this.prods = []

         },
         customFormatter(date) {
            return moment(date).format('DD/MM/YYYY');
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
                  client_id: this.header_order.client_id,
               }
            }).then(({data}) => {
               this.prods = data;
               if (data.length === 0)
                  this.noData = true

               this.loading = false;
            })
         },
         addOrder() {
            const {cmd_number, date_cmd} = this.header_order
            const products = this.commands.map(p => {
               return {
                  'product_id': p.product_id,
                  'qte': Number(p.qte),
                  'qte_price': ((p.prix - p.discount) * Number(p.qte) * p.qte_rapport).toFixed(2),
               }
            })

            axios.post('/api/orders', {date_cmd, cmd_number, products})
               .then(({data}) => {
                  console.log(data)
               })
               .catch(err => {
                  const {cmd_number, date_cmd} = err.response.data.errors

                  if (cmd_number !== undefined)
                     this.$notification.error(cmd_number[0])
                  if (date_cmd !== undefined)
                     this.$notification.error(date_cmd[0])

                  console.log(err.response)
               })
         }
      },
      watch: {
         dateCmd: function (d) {
            this.header_order.date_cmd = moment(d).format('DD/MM/YYYY');
         },
         putOrder: function (o) {
            this.selected_order = {...o}
         }
      },

   }
</script>

<style scoped>

</style>
