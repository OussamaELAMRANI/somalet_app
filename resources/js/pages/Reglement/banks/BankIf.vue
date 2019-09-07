<template lang="pug">
   #if_bank
      h2.text-primary IF
      table-layout(theme="bg-primary text-white", :head-table="ifItems")
</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";
    export default {
        name: "BankIf",
       data(){
         return{
            ifItems:['#','commandes', 'Montant']
         }
       },
       components: {TableLayout}
    }
</script>

<style scoped>

</style>
