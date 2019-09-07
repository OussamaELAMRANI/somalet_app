<template lang="pug">
   #orders_list
      router-link(:to="{name:'newOrder'}").btn.btn-sm.btn-outline-success
         i.fa.fa-backward
         | {{' Nouveau Command'}}
      big-title(title="Liste des commandes !")
      table-layout(:head-table="tableItems", empty-text='Pas de commande', :data='orders')
         tr(v-for='(o,i ) in orders')
            td {{i+1}}
            td {{o.cmd_number}}
            td {{o.date_cmd}}
            td {{o.client.lastName}} {{o.client.firstName}}
            td
               button.btn.btn-sm.btn-dark
                  i.fa.fa-trash
               router-link.btn.btn-sm.btn-primary(:to="{name:'detailOrder',params:{id:o.cmd_number}}")
                  i.fa.fa-list

</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "OrdersList",
      data() {
         return {
            tableItems: ['#', 'Numéro de livrison', 'date', 'Client', 'Actions'],
            orders: null
         }
      },
      mounted() {
         this.getOrders()
      },
      methods: {
         getOrders() {
            axios.get('/api/orders')
               .then(({data}) => {
                  this.orders = data
                  console.log(data)
               })
               .catch(err => console.log(err.response))
         }
      },
      components: {TableLayout, BigTitle}
   }
</script>

<style scoped>
   td > button {
      margin-right: 2px;
   }
</style>
