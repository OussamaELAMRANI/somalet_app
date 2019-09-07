<template lang="pug">
   #reglement
      .row.justify-content-center.align-items-center
         .col-3
            box(img='boxes/money-bag.svg' title='CAISSE' h='h4' size='70px' subtitle='Consulter la caisee maintenant' link='caisse' )
         .col-3
            box(img='boxes/suitcase.svg' title='PORTFEUILLE' h='h4' size='70px' subtitle="Verifier l'état des chéque" link='portfeuille' )
         .col-3
            box(img='boxes/bank.svg' title='BANQUE' h='h4' size='70px' subtitle="Gérer les banques ici ..." link='banks')

      .container
         router-view


</template>

<script>
   import Box from "@/components/utils/Box";
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "Reglements",
      data() {
         return {
            items: ['#', 'Commande', 'Montant']
         }
      },
      components: {TableLayout, Box}
   }
</script>

<style scoped>
   /*#reglement .row {*/
   /*   height: 90vh;*/
   /*}*/
</style>
