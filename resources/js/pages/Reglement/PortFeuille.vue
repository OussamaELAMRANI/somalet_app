<template lang="pug">
   #port_feuille
      h2.text-secondary.text-uppercase Portfeuille
      hr
      table-layout(:head-table="items", :data="checkout", empty-text="Pas des cheques ou des éffets $ !" theme="bg-primary small text-white")
         tr(v-for=" (p, i) in checkout")
            td {{i+1}}
            td {{p.payed_at | humane_date}}
            td(:class="(p.date_deadline <= p.payed_at )? 'bg-warning': 'bg-success text-white'") {{p.date_deadline | humane_date}}
            td {{p.amount + ' DH'}}
            td
               router-link(:to="{name:'movementClient', params:{id:p.client.id}}") ({{p.client.id}}) {{p.client.nom }}
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
      name: "PortFeuille",
      components: {TableLayout},
      data() {
         return {
            items: ['#', 'Date de payement', "Date d'écheance", 'Montant', 'Client', 'Action'],
            checkout: null
         }
      },
      mounted() {
         this.getCheckout()
      },
      methods: {
         getCheckout() {
            axios.get('/api/payments/cheques')
               .then(({data}) => {
                  this.checkout = data;
                   console.log(data)
               })
               .catch(({response}) => console.log(response))

         }
      },
   }
</script>

<style scoped>

</style>
