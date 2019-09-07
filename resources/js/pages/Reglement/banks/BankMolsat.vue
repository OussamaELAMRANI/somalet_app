<template lang="pug">
   #molsat_bank
      h2.text-success MOLSAT
      table-layout(theme="bg-success text-white", :head-table="molsatItems")
</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";
    export default {
        name: "BankMolsat",
       data(){
          return{
             molsatItems:['#','commandes', 'Montant']
          }
       },
       components: {TableLayout}
    }
</script>

<style scoped>

</style>
