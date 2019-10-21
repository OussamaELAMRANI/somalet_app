<template lang="pug">
   #order_detail.my-5
      router-link(:to="{name:'newOrder'}").btn.btn-sm.btn-outline-success
         i.fa.fa-backward
         | {{' Nouveau Commande'}}
      big-title(title="Détail Commande")
      .row.justify-content-center
         template(v-if='order != null')
         .col-7.mb-4
            table-layout(:head-table="tableItems", empty-text='Pas de commande', :data='order' key="cmd-header")
               tr
                  td {{client.id}}
                  td {{ order.cmd_number}}
                  td {{ order.date_cmd}}
                  td
                     strong {{client.nom}}
         .col-6
            h4.text-uppercase.text-secondary Détail des produits
            hr
            table-layout(:head-table="detailItems", theme='bg-primary text-white', empty-text='Pas de Detail', :data='products' key="cmd-detail")
               tr(v-for='(p,i ) in products')
                  td {{i+1}}
                  td {{p==null ? null: p.reference}}
                  td {{p==null ? null: p.name}}
                  td
                     ul.list-group.list-group-horizontal
                        li.list-group-item.m-1.p-0.w-50 {{p.pivot.qte}}
                        li.list-group-item.my-1.p-0.bg-success
                        li.list-group-item.m-1.p-0.w-50.bg-success.text-white {{p.pivot.qte_rapport}}
                        li.list-group-item.my-1.p-0.bg-warning
                        li.list-group-item.my-1.p-0.bg-warning
                        li.list-group-item.m-1.p-0.w-50.bg-primary.text-white {{p.pivot.qte * p.pivot.qte_rapport}}
                  td {{p==null ? null: p.pivot.discount}}
                  td {{p==null ? null: p.pivot.price}}
                  td {{ (p.pivot.price - p.pivot.discount )* p.pivot.qte * p.pivot.qte_rapport}}
               tr.segment
                  td(:colspan="detailItems.length -1") TOTAL en (DH)
                  td.bg-primary.text-white {{orderTotal}}

         .col-6
            h4.text-uppercase.text-secondary Payéments flow
            hr
            table-layout(:head-table="paymentsItems", theme='bg-success text-white', empty-text='Pas de Paiément', :data='payments' key="cmd-payment")
               tr(v-for='(p,i ) in payments')
                  td {{i+1}}
                  td {{p==null ? null: p.payed_at | humane_date}}
                  td {{p==null ? null: p.type}}
                  td {{p==null ? null: (( p.type !== 'ESP')? p.date_deadline: null ) | humane_date}}
                  td {{p==null ? null: p.amount}}
                  tr.segment
                     td(:colspan="paymentsItems.length -1") TOTAL en (DH)
                     td.bg-success.text-white {{paymentTotal}}
                  tr.segment
                     td(:colspan="paymentsItems.length -1") Reste en (DH)
                     td.text-white.bg-primary {{orderTotal- paymentTotal}}
            hr
            router-link(:to="{name:'newPayment', params:{ cmd:order.cmd_number}}" class="btn btn-outline-success mb-2 float-right")
               i.fa.fa-money-bill
               | {{' Ajouter Payément'}}

</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";
    import BigTitle from "@/components/layouts/BigTitle";

    export default {
        name: "DetailOrder",
        components: {BigTitle, TableLayout},
        data() {
            return {
                tableItems: ['ID', 'Numéro de livrison', 'date', 'Client'],
                detailItems: ['#', 'Reference', 'Designation', 'Quantité', 'Remise', 'Prix', 'Mantant'],
                paymentsItems: ['#', 'Date de payément', 'Mode de payement', "Date d'échances", 'Montant'],
                order: {
                    cmd_number: '',
                    date_cmd: '',
                },
                products: [],
                payments: [],
                client: {
                    id: '',
                    nom: '',
                },
                orderTotal: 0,
                paymentTotal: 0,
            }
        },
        mounted() {
            this.getOrder()
        },
        methods: {
            getOrder() {
                const id = this.$route.params.id;
                axios.get(`/api/orders/${id}`)
                    .then(({data}) => {
                        const {product, payments, client} = data;
                        this.products = product;
                        this.payments = payments;
                        this.client = client;

                        _.forEach(data.payments, (p) => {
                            this.paymentTotal += p.amount
                        })
                        _.forEach(product, (p) => {
                            this.orderTotal += (p.pivot.price - p.pivot.discount) * p.pivot.qte * p.pivot.qte_rapport;
                        })
                        this.order = data
                    })
                    .catch(err => {
                        console.log(err.response)
                        this.$router.push("/404")
                        this.$notification.error("cette commande n'existe pas !")
                    })
            },
            getTotal(v, type) {
                v = Number(v)
                if (type === 'P') {
                    this.paymentTotal += v
                } else {
                    this.orderTotal += v
                }
            }
        },
        computed: {
            getCommand() {
                return this.order
            }
        }
    }
</script>

<style scoped>
.segment{
   border-bottom: 2px orange solid;
}
</style>
