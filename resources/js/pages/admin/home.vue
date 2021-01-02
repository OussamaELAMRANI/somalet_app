<template>
   <div id="home" class="my-5">
      <h3 class="text-center text-uppercase text-secondary m-5 ar">Tableau de Bord</h3>
      <hr>
      <!--      <div class="row">-->
      <!--         <div class="col-3">-->
      <!--            <Box img="boxes/meeting.svg" title="Équipe" h="h4"-->
      <!--                 size="70px" subtitle="Équipe de travail" link="list_users" :hasState="null"/>-->
      <!--         </div>-->
      <!--         <div class="col-3">-->
      <!--            <Box img="boxes/settings.svg" title="Paramétrage" h="h4"-->
      <!--                 size="70px" subtitle="Paramètrer votre application" link="settings" :hasState="null"/>-->
      <!--         </div>-->
      <!--      </div>-->
      <!--      <hr>-->
      <div class="row">
         <div class="col-3" @mouseover="showAlert" @mouseleave="hideAlert">
            <Box img="boxes/stock.svg" title="STOCK" h="h4"
                 size="70px" subtitle="Consulter les alert du stock" :link="{name:'stockAlert'}" :hasState="{}">
               <router-link :to="{name:'stockAlert'}" id="alert" data-trigger="hover" data-toggle="popover"
                            title="Alert de Stock"
                            :html="true"
                            :data-content="'Il apparaît que il y a un manque dont '+  alert.length +'\n Produit(s) dans le Stock'">
                  <p class="badge badge-danger mr-1">
                     <i class="fa fa-exclamation-triangle"></i>
                     {{ alert.length }}
                  </p>

               </router-link>
            </Box>
         </div>

         <div class="col-3">
            <Box img="boxes/money.svg" title="CHEQUES" h="h4"
                 size="70px" subtitle="Échéance de ce mois"
                 :link="{name:'monthBilling',params:{year:now.getFullYear(), month: now.getMonth()+1}}" :hasState="{}">
               <p class="badge badge-danger mr-1">
                  <i class="fa fa-exclamation-triangle"/>
                  {{ deadline | getByChoice('count') }}
               </p>
            </Box>
         </div>

         <!--         <div class="col" v-if="alert.length > 0">-->
         <!--            <div class="row">-->
         <!--               <div class="col-2" v-for="a in alert">-->
         <!--                  <div class="alert alert-danger alert-dismissible fade show" role="alert">-->
         <!--                     <p class="alert-heading">{{ a.reference }}</p>-->
         <!--                     <p class="text-success font-weight-bolder m-0">-->
         <!--                        {{ a.totalQte }} < {{ a.alert }}-->
         <!--                     </p>-->

         <!--                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">-->
         <!--                        <span aria-hidden="true">&times;</span>-->
         <!--                     </button>-->
         <!--                  </div>-->
         <!--               </div>-->
         <!--            </div>-->

         <!--         </div>-->
         <!--         <div class="col-3">-->
         <!--            <Box img="boxes/cargo.svg" title="ARRIVAGE" h="h4"-->
         <!--                 size="70px" subtitle="Liste des Arrivages" link="list_arrivals" :hasState="{}"/>-->
         <!--         </div>-->
         <!---->
         <!--         <div class="col-3">-->
         <!--            <Box img="boxes/bill.svg" title="COMMANCDE" h="h4"-->
         <!--                 size="70px" subtitle="Liste des commandes" link="allOrders" :hasState="{}"/>-->
         <!--         </div>-->

      </div>
      <hr>
      <!--      <div class="row justify-content-start">-->
      <!--         &lt;!&ndash;&ndash;&gt;-->
      <!--         <div class="col-3">-->
      <!--            <Box img="boxes/providers.svg" title="FOURNISSEURS" h="h4"-->
      <!--                 size="70px" subtitle="Gérer les fournisseurs" link="allProviders" :hasState="{}"/>-->
      <!--         </div>-->
      <!--         <div class="col-3">-->
      <!--            <Box img="boxes/shoes.svg" title="PRODUIT" h="h4"-->
      <!--                 size="70px" subtitle="Liste des produits" link="listProducts" :hasState="{}"/>-->
      <!--         </div>-->
      <!--         <div class="col-3">-->
      <!--            <Box img="boxes/cutomers.svg" title="CLIENTS" h="h4"-->
      <!--                 size="70px" subtitle="Nos clients" link="allClients" :hasState="{}"/>-->
      <!--         </div>-->
      <!--         <div class="col-3">-->
      <!--            <Box img="boxes/money.svg" title="" h="h4"-->
      <!--                 size="70px" subtitle="Gérer les paiéments des commandes" link="allBillings" :hasState="{}"/>-->
      <!--         </div>-->

      <!--      </div>-->
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
         alert: [],
         peace: null,
         deadline: [],
         now: new Date()
      }
   },
   mounted() {
      axios.get('/api/inventories/alert')
         .then(({data}) => this.alert = data)

      axios.get('/api/payments/deadline-count')
         .then(({data}) => this.deadline = data)

      this.peace = $('#alert')
   },
   created() {

   },
   methods: {
      showAlert() {
         if (this.alert.length > 0) {
            this.peace.popover('enable')
            this.peace.popover('show')
         }
      },
      hideAlert() {
         if (this.alert.length > 0) {
            this.peace.popover('hide')
            this.peace.popover('disable')
         }
      }
   },
   destroyed() {
      this.peace.popover('hide')
      this.peace.popover('disable')
   }
}
</script>

<style scoped>

</style>
