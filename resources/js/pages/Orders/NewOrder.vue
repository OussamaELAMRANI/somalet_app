<template lang="pug">
   #new_order.my-4.clearfix
      big-title(title="Nouveau Commande" position="text-center" :classes="['h3','text-secondary','text-uppercase','my-5']")
      .row
         .col-8
            .row
               .col-12
                  form.row
                     .col
                        .form-group
                           label Client
                           cool-select(:items='clients' :item-text="'nom'" item-value='id'
                              name='client' placeholder="Nom ou Prénom" v-model='header_order.client_id'
                              v-validate="'required'")
                              template(slot='item' slot-scope='{ item:p }')
                                 p
                                    strong ({{p.id + ') '}}
                                    | {{ p.lastName }}
                                    strong {{ ' '+ p.firstName }}
                           span.help.text-danger(v-show="errors.has('client')") Vous devez selectionner le client
                     .col
                        .form-group
                           label Date de la Facture :
                           datepicker.form-control(:format='customFormatter' :language='fr' :monday-first='true'
                              calendar-button-icon='fa fa-user' name='date_picker' v-model='dateCmd')
                     .col
                        .form-group
                           label(for="delivery") Numéro de Livraison
                           input(class='form-control' type='text', placeholder='Numero de Facture ...' id='delivery' v-model='header_order.cmd_number' disabled)
                           span.help.text-danger(v-show="errors.has('cmd_number')") Vous devez Saisire le numéro de la livraison
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
                        tr.segment(v-if="commands.length === 0")
                           td(colspan='7')
                              h4.text-center.text-uppercase.font-italic  Pas de produit !
                        template(v-else,  v-for="(order, i) in commands" )
                           tr(v-for="(v,k) in order.qte_cmd")
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
                                 button.btn.btn-outline-danger.btn-sm.rounded-pill.shadow(@click="deleteItem(order.product_id)")
                                    i.fa.fa-minus
               .col-12
                  button(@click="addOrder" class='btn btn-primary text-white float-right' v-if="commands.length !== 0") Enregister

         .col-4

            form(@submit.prevent="" v-if="header_order.client_id != null")
               .row.justify-content-center.my-4.segment.shadow
                  .col-12
                     h4.text-primary.text-capitalize Selectionnez produit
                  .col-12
                     .form-group
                        label Rechercher par désignation de produit
                        cool-select(:items='prods' @search='onSearch' :loading='loading'
                           item-text='name' name='produit' v-model='putOrder' v-validate="'required'" )
                           //item-value='id'
                           template(slot='item' slot-scope='{ item:p }')
                              .d-flex
                                 div
                                    p {{ p.name }} -
                                       span.text-primary.font-weight-bolder {{ p.reference }}
                           template(slot='no-data') {{ noData ? "Aucun produit trouvée" :"Chercher par nom de produit" }}
                     hr
                  transition(name="expand" @enter="enter" @after-enter="afterEnter" @leave="leave")
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

                        .col-12(v-if="isEmpty")
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

    export default {
        name: "NewOrder",
        components: {TableLayout, OrderTable, BigTitle, CoolSelect, Datepicker},
        mounted() {
            this.getClients()
            this.getOrderNumber()
        },
        data() {
            return {
                fr,
                dateCmd: moment().toDate(),
                header_order: {
                    client_id: '',
                    cmd_number: null,
                    date_cmd: moment().toDate(),
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
                    discount: 0,
                    QTE: {},
                    QTE_TOTAL: {}
                },
                putOrder: null,
                qteError: false,
                isEmpty: false
            }
        },
        methods: {
            getClients() {
                axios.get('/api/clients')
                    .then(({data}) => {
                        this.clients = data
                        console.log(data)
                    })
                    .catch(err => console.log(err.response))
            },
            getOrderNumber() {
                axios.get('/api/orders/cmd_number')
                    .then(({data}) => {
                        const {cmd_number} = data;
                        this.header_order.cmd_number = Number(cmd_number) + 1
                    })
            },
            getOrders() {
                let hasError = false
                const qte = {'qte_cmd': {...this.qte}}
                const products = this.putOrder
                const total = {...products.QTE_TOTAL}

                if (Object.keys(qte.qte_cmd).length === 0) {
                    this.isEmpty = true;
                    return;
                } else if (Object.values(qte.qte_cmd)[0] == "") {
                    this.isEmpty = true;
                    return;
                } else {
                    this.isEmpty = false
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
                this.deleteItem(command.product_id)
                this.commands.unshift(command)

                this.qte = []
                this.putOrder = []

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
                const order = this.header_order
                const products = this.commands.map(p => {

                    // p.qte_cmd.map((k, v) => {
                    //
                    // })
                    const qte = p.qte_cmd
                    const qte_rapport = Object.keys(p.qte_cmd)

                    return {
                        'product_id': p.product_id,
                        qte,
                        qte_rapport,
                        'price': Number(p.price),
                        'discount': Number(p.discount).toFixed(2),
                    }
                })

                axios.post('/api/orders', {order, products})
                    .then(({data}) => {
                        console.log(data)
                        this.$router.push({name: 'listOrder'})
                        this.$notification.success("La commande a été bien ajouté ! ")
                    })
                    .catch(err => {
                        const {cmd_number, date_cmd} = err.response.data.errors

                        if (cmd_number !== undefined)
                            this.$notification.error(cmd_number[0])
                        if (date_cmd !== undefined)
                            this.$notification.error(date_cmd[0])

                        console.log(err.response)
                    })
            },
            deleteItem(id) {
                this.commands = this.commands.filter(i => {
                    return (id !== i.product_id)
                })
            },
            // ANIMATION =============
            enter(element) {
                const width = getComputedStyle(element).width;

                element.style.width = width;
                element.style.position = 'absolute';
                element.style.visibility = 'hidden';
                element.style.height = 'auto';

                const height = getComputedStyle(element).height;

                element.style.width = null;
                element.style.position = null;
                element.style.visibility = null;
                element.style.height = 0;

                // Force repaint to make sure the
                // animation is triggered correctly.
                getComputedStyle(element).height;

                // Trigger the animation.
                // We use `setTimeout` because we need
                // to make sure the browser has finished
                // painting after setting the `height`
                // to `0` in the line above.
                setTimeout(() => {
                    element.style.height = height;
                });
            },
            afterEnter(element) {
                element.style.height = 'auto';
            },
            leave(element) {
                const height = getComputedStyle(element).height;

                element.style.height = height;

                // Force repaint to make sure the
                // animation is triggered correctly.
                getComputedStyle(element).height;

                setTimeout(() => {
                    element.style.height = 0;
                });
            },
        },
        watch: {
            dateCmd: function (d) {
                this.header_order.date_cmd = moment(d).format('DD/MM/YYYY');
            },
            putOrder: function (o) {
                this.qte = []; // To Avoid Putting Invalid data to Commands
                this.selected_order = {...o}
            }
        },

    }
</script>

<style scoped>
   .expand-enter-active,
   .expand-leave-active {
      transition: height .6s ease-in-out;
      overflow: hidden;
   }

   .expand-enter,
   .expand-leave-to {
      height: 0;
   }
</style>
