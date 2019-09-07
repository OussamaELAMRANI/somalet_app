<template lang="pug">
   #list_stock
      big-title(title="STOCKE RECEPTIONNE" position="text-left" classes="text-primary")
      .row
         .col-10
            stock-table(:headTable="tabHeader" )
</template>

<script>
   import StockTable from "@/components/Stock/StockTable";
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "StockList",
      components: {BigTitle, StockTable},
      data() {
         return {
            tabHeader: ['', 'Facture', 'Dossier', 'Date'],
            arrivals: [
               {
                  n_facture: '',
                  n_dossier: '',
                  date_facture: '',
               }
            ]
         }
      },

   }
</script>

<style scoped>

</style>
