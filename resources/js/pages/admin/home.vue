<template>
   <div id="home" class="my-5">
      <h3 class="text-center text-uppercase text-secondary m-5 ar">Tableau de Bord</h3>
      <hr>
      <div class="row justify-content-center">
         <div class="col-3">
            <Box img="boxes/meeting.svg" title="Équipe" h="h4"
                 size="70px" subtitle="Équipe de travail" link="list_users" :hasState="null"/>
         </div>
         <div class="col-3">
            <Box img="boxes/settings.svg" title="Paramétrage" h="h4"
                 size="70px" subtitle="Paramètrer votre application" link="settings" :hasState="null"/>
         </div>
      </div>
      <hr>
      <div class="row justify-content-center">

         <div class="col-3">
            <Box img="boxes/providers.svg" title="FOURNISSEURS" h="h4"
                 size="70px" subtitle="Gérer les fournisseurs" link="allProviders" :hasState="{}"/>
         </div>
         <div class="col-3">
            <Box img="boxes/shoes.svg" title="PRODUIT" h="h4"
                 size="70px" subtitle="Liste des produits" link="listProducts" :hasState="{}"/>
         </div>
         <div class="col-3">
            <Box img="boxes/cutomers.svg" title="CLIENTS" h="h4"
                 size="70px" subtitle="Nos clients" link="allClients" :hasState="{}"/>
         </div>
      </div>
      <hr>
      <div class="row  justify-content-center">
         <div class="col-3">
            <Box img="boxes/money.svg" title="PAIÉMENT" h="h4"
                 size="70px" subtitle="Gérer les paiéments des commandes" link="allBillings" :hasState="{}"/>
         </div>
         <div class="col-3">
            <Box img="boxes/profits.svg" title="RÉGLEMENT" h="h4"
                 size="70px" subtitle="Gérer les Banques/ Caisse ..." link="reglements" :hasState="{}"/>
         </div>
      </div>
      <hr>
      <div class="row">
         <div class="col-3">
            <Box img="boxes/stock.svg" title="STOCK" h="h4"
                 size="70px" subtitle="Consulter les enters quotidiennement" link="inventories" :hasState="{}"/>
         </div>
         <div class="col-3">
            <Box img="boxes/cargo.svg" title="ARRIVAGE" h="h4"
                 size="70px" subtitle="Liste des Arrivages" link="list_arrivals" :hasState="{}"/>
         </div>

         <div class="col-3">
            <Box img="boxes/bill.svg" title="COMMANCDE" h="h4"
                 size="70px" subtitle="Liste des commandes" link="allOrders" :hasState="{}"/>
         </div>
      </div>

   </div>
</template>

<script>
   import Box from "../../components/utils/Box";
   // import store from '@/store'

   export default {
      name: "home",
      components: {Box},
      data() {
         return {
            // my_routes: store.getters.permission_routes
         }
      },
      computed: {
         // filterRoutes() {
         //     const routes = _.cloneDeep(this.my_routes)
         //     return _.filter(routes, (r) => r.hidden !== true)
         // }
      }
   }
</script>

<style scoped>

</style>
