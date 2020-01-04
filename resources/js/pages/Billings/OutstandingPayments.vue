<template lang="pug">
   #out
      h4 Liste des impayés
      hr
      .row
         table-layout(theme="bg-primary text-white", :head-table="ifItems", data="payments", empty-text="Pas de payement")
            tr(v-for="p in payments")
               td {{p.id}}
               td {{p.payed_at | humane_date}}
               td
                  p N:
                     strong {{p.chq_number}}
                  p {{p.designation}}
               td {{`${p.amount} DH`}}
               td {{(p.adjust_by === null) ? 'En cours' : 'Régler'}}
               td
                  router-link.btn.btn-block.btn-success(v-if="p.adjust_by === null", :to="{name:'adjust', params:{id:p.id}}" ) Régler
</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "OutstandingPayments",
      components: {TableLayout},
      data() {
         return {
            ifItems: ['#', 'Date', 'Détail', 'Montant', 'Régler par', 'Action'],
            payments: null
         }
      },
      mounted() {
         axios.get('/api/payments/outstanding')
            .then(({data}) => {
               // const {payments} = data
               this.payments = data;
            }).catch(error => console.log(error));
      }
   }
</script>

<style scoped>

</style>
