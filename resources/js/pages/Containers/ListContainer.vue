<template>
   <div class="">
      <alert-modal title="Supperession d'un contenaire" id="bitch">
         <template slot="content">
            <h5 class="text-uppercase text-danger text-center">
               Voullez-vous vraiment supprimer ce contenaire ?
            </h5>
         </template>
         <template slot="btn">
            <button class="btn btn-danger" @click="supp()" data-dismiss="modal">Supprimer</button>
         </template>
      </alert-modal>

      <big-title title="Liste Contenaires"/>
      <div class="row justify-content-center">

         <div class="col-10 my-2">
            <table-layout theme="bg-primary small text-white text-center" :headTable="tabHeader" :data="containers"
                          empty-text="Pas de contenaire en cours..">
               <tr v-for=" (c,j) in containers">
                  <td>{{j+1}}</td>
                  <td>{{c.n_dossier}}</td>
                  <td>{{c.date_facture | humane_date}}</td>
                  <td :class="(c.state != 'ATTENTE')? 'text-success font-weight-bolder':'text-primary'">{{c.state}}</td>
                  <td class="text-info">
                     <button class="btn btn-outline-danger  rounded-pill shadow" @click="getElementIdToDelete(c.id)"
                             data-target="#bitch" data-toggle="modal" v-if="c.state !== 'VALID' ">
                        <i class="fa fa-trash my-1"/> Supprimer
                     </button>
                     <router-link class="btn btn-primary rounded-pill text-white shadow"
                                  :to="{name:'ContainerDetail', params:{id:c.id}}">
                        <i class="fa fa-list mx-2"/> Détail
                     </router-link>
                  </td>
               </tr>
            </table-layout>
         </div>
         <!--         <div class="col mx-2 segment filter shadow">-->
         <!--            <h5 class="text-primary">Filter par:</h5>-->
         <!--            <hr>-->
         <!--            <div class="form-group">-->
         <!--               <label for="">Contenaire</label>-->
         <!--               <input type="text" class="form-control rounded-pill" placeholder="Numéro du contenaire ...">-->
         <!--            </div>-->
         <!--            <div class="form-group">-->
         <!--               <label for="">Date d'enter</label>-->
         <!--               <input type="text" class="form-control rounded-pill" placeholder="Par Date ...">-->
         <!--            </div>-->
         <!--            <hr>-->
         <!--            <button class="btn btn-outline-primary btn-block rounded-pill">Filter</button>-->
         <!--         </div>-->
      </div>
   </div>
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import TableLayout from "@/components/layouts/TableLayout";
   import AlertModal from "@/components/Modals/AlertModal";

   export default {
      name: "ListContainer",
      data() {
         return {
            containers: null,
            product: {},
            detail: [],
            tabHeader: ['#', 'Numero de contenaire', 'Date', 'Status', 'Actions'],
            urlBase: '/api/arrivals',
            ElementIdToDelete: null
         }
      },
      mounted() {
         this.getContainers()
      },
      methods: {
         getElementIdToDelete(id) {
            this.ElementIdToDelete = id;
         },
         getContainers() {
            axios.get(this.urlBase, {
               params: {
                  with: 'product&status=attent,vue,reception'
               }
            }).then(({data}) => {
               this.containers = data
            })
               .catch(err => console.log(err.response))
         },
         supp() {
            let id = this.ElementIdToDelete;
            axios.delete(`${this.urlBase}/${id}/delete`)
               .then(res => {
                  this.containers = null;
                  this.getContainers();
                  this.$notification.success("Supression en sccess")

               })
               .catch(err => {
                  this.$notification.error("Ce produit n'existe pas !")
               })
         },

         // getDetail(id, index) {
         //     console.log(id)
         //     console.log(index)
         //
         //     axios.get(`/api/products/${id}?with=arrivals,provider`)
         //         .then(({data}) => {
         //             console.log(data)
         //             this.detail[index] = data
         //         })
         //         .catch(err => console.log(err.response))
         // },
      },
      components: {AlertModal, TableLayout, BigTitle}
   }
</script>

<style lang="scss" scoped>
   $orange: #ff9f42;

   table {
      width: 100%;
      table-layout: fixed;
   }

   .tbl-content {
      max-height: 300px;
      overflow-x: auto;
      margin-top: 0;
      border: 1px solid rgba(255, 255, 255, 0.3);
   }

   .segment {
      margin: 0;
      border-bottom: 2px solid $orange;
   }

   .filter {
      max-height: 700px;
      overflow: auto;
   }
</style>
