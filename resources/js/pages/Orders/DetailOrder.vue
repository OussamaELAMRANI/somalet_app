<template lang="pug">
   #order_detail.container.my-5
      router-link(:to="{name:'newOrder'}").btn.btn-sm.btn-outline-success
         i.fa.fa-backward
         | {{' Nouveau Commande'}}
      big-title(title="Détail Commande")

      template(v-if='order != null')
         table-layout(:head-table="tableItems", empty-text='Pas de commande', :data='order' key="cmd-header")
            tr
               td {{'X'}}
               td {{order==null ? null: order.cmd_number}}
               td {{order==null ? null: order.date_cmd}}
               td
                  strong {{order==null ? null: order.client.lastName}} {{order==null ? null: order.client.firstName}}

         table-layout(:head-table="detailItems", theme='bg-primary text-white', empty-text='Pas de Detail', :data='order.product' key="cmd-detail")
            tr(v-for='(p,i ) in order.product')
               td {{i+1}}
               td {{p==null ? null: p.reference}}
               td {{p==null ? null: p.name}}
               td {{p==null ? null: p.pivot.qte}}
               td {{p==null ? null: p.pivot.qte_price}}
            tr.segment
               td(:colspan="detailItems.length -1") TOTAL
               td.bg-secondary.text-white {{orderTotal}} DH

         h4.text-uppercase.text-secondary Payéments flow
         hr
         router-link(:to="{name:'newPayment', params:{ cmd:order.cmd_number}}" class="btn btn-outline-success mb-2 float-right")
            i.fa.fa-money-bill
            | {{' Ajouter Payément'}}
         table-layout(:head-table="paymentsItems", theme='bg-success text-white', empty-text='Pas de Paiément', :data='order.payments' key="cmd-payment")
            tr(v-for='(p,i ) in order.payments')
               td {{i+1}}
               td {{p==null ? null: p.payed_at | humane_date}}
               td {{p==null ? null: p.type}}
               td {{p==null ? null: (( p.type !== 'ESP')? p.date_deadline: null ) | humane_date}}
               td {{p==null ? null: p.amount}}
               tr.segment
                  td(:colspan="paymentsItems.length -1") TOTAL
                  td.bg-secondary.text-white {{paymentTotal}} DH


</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "DetailOrder",
      components: {BigTitle, TableLayout},
      data() {
         return {
            tableItems: ['#', 'Numéro de livrison', 'date', 'Client'],
            detailItems: ['#', 'Reference', 'Designation', 'Quantité', 'Mantant'],
            paymentsItems: ['#', 'Date de payément', 'Mode de payement', "Date d'échances", 'Montant'],
            order: null,
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
                  _.forEach(data.payments, (p) => {this.paymentTotal += p.amount})
                  _.forEach(data.product, (p) => {this.orderTotal += p.pivot.qte_price})
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
      }
      ,
      computed: {
         getCommand() {
            return this.order
         }
      }
   }
</script>

<style scoped>

</style>
