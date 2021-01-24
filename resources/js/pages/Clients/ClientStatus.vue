<template>
   <div id="client-list">
      <div class="row justify-content-end">
         <div class="col-3">
            <button @click="print"
                    class="btn btn-primary text-uppercase font-weight-bolder float-right btn-block ">
               Imprimer
            </button>
         </div>
      </div>
      <div class="row" id="printMe">
         <div class="col-9">
            <h3>Etats des clients</h3>
            <hr>
         </div>
         <div class="col-12">
            <table-layout :head-table="['ID','Nom','Prénom','Soldes']" :data="clients"
                          empty-text="Pas de client dans ce cas (Vide...)" theme="bg-primary text-white">
               <tr>
                  <td colspan="3" v-if="isloading">
                     <div class="row justify-content-center bg-white">
                        <div class="col-2">
                           <half-circle-spinner color="#f83896" :size="40"/>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr v-for="(p) in clients" :id="p.client">
                  <td>{{ p.client | getByChoice('id') }}</td>
                  <td>{{ p.client | getByChoice('lastName') }}</td>
                  <td>{{ p.client | getByChoice('firstName') }}</td>
                  <td class="font-weight-bolder">{{ p.sold_today }} DH</td>
               </tr>
               <tr>
                  <td colspan="3" class="text-right font-weight-bolder">
                     <h5 class="text-left">
                        Date: <strong>{{new Date() | full_date}}</strong>
                     </h5>
                     <h5>Total:</h5>
                  </td>
                  <td class="font-weight-bolder">
                     <h5>
                        {{ getTotal | fixed_two}} DH
                     </h5>
                  </td>
               </tr>
            </table-layout>
         </div>
      </div>
   </div>
</template>

<script>
import TableLayout from "@/components/layouts/TableLayout";

export default {
   name: "ClientStatus",
   data() {
      return {
         isloading: false,
         clients: [],
         total: 0
      }
   },
   mounted() {
      this.isloading = true;
      axios.get(`/api/clients/status`)
         .then(({data}) => {
               this.clients = data;
               this.isloading = false;
            }
         )
         .catch(err => console.log(err.response))
   },
   computed: {
      getTotal() {
         return (this.clients.length > 0) ?
            _.sumBy(this.clients, function (c) {
               return c.sold_today;
            }) : 0;
      }
   },
   methods: {
      print() {
         this.$htmlToPaper('printMe');
      }
   },
   components: {
      TableLayout,
   }
}
</script>

<style scoped>

</style>
