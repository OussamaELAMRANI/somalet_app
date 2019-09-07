<template lang="pug">
   #money-bag
      h2.text-secondary.text-uppercase Caisse
      hr
      table-layout(:head-table="items", :data="checkout", empty-text="Pas d'agent $ !")
         tr(v-for=" (p, i) in checkout")
            td {{i+1}}
            td {{p.payed_at | humane_date}}
            td {{p.order.cmd_number}}
            td {{p.amount + ' DH'}}
            td {{p.order.client.firstName +' '}}
               strong {{p.order.client.lastName}}
            td
               button.btn-outline-primary.btn.btn-sm
                  i.fa.fa-university
                  | {{' Molsat'}}
               button.btn-outline-success.btn.btn-sm
                  i.fa.fa-university
                  | {{' IF'}}
</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "Caisse",
      data() {
         return {
            items: ['#', 'Date de payement', 'Commandes', 'Montant', 'Client', 'Action'],
            checkout: null
         }
      },
      mounted() {
         this.getCheckout()
      },
      methods: {
         getCheckout() {
            axios.get('/api/payments/checkout')
               .then(({data}) => {
                  this.checkout = data
               })
               .catch(({response}) => console.log(response))

         }
      },
      components: {TableLayout}
   }
</script>

<style scoped>
   td button{
      margin-right: 4px;
   }
</style>
