<template>
   <div id="client-list">
      <alert-modal title="Supperession d'un client" id="bitch">
         <template slot="content">
            <h5 class="text-uppercase text-danger text-center">
               Voullez-vous vraiment supprimer ce client ?
            </h5>
         </template>
         <template slot="btn">
            <button class="btn btn-danger" @click="supp()" data-dismiss="modal">Supprimer</button>
         </template>
      </alert-modal>
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
         <div class="modal-dialog modal-xl" role="">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Detail d'un Client</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="alert segment">
                     <show-client :client="client"/>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  <button type="button" class="btn btn-primary">Imprimer</button>
               </div>
            </div>
         </div>
      </div>
      <router-link :to="{name:'newClient'}" class="btn btn-success btn-sm">
         <i class="fa fa-plus mx-2"></i>
         Nouveau Client
      </router-link>
      <h3 class="text-lg-center text-secondary">Liste des clients</h3>
      <hr>
      <div class="row">
         <div class="col-9">
            <table-layout :head-table="['ID','Nom','Prénom','Actions']" :data="clients"
                          empty-text="Pas de client dans ce cas (Vide...)" theme="bg-primary text-white small">
               <tr>
                  <td colspan="4" v-if="isloading">
                     <div class="row justify-content-center bg-white">
                        <div class="col-2">
                           <half-circle-spinner color="#f83896" :size="40"/>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr v-for="(p) in clients" :id="p.id">
                  <td>{{p.id}}</td>
                  <td>{{p.lastName}}</td>
                  <td>{{p.firstName}}</td>
                  <td>
                     <button class="btn btn-sm btn-outline-info rounded-pill shadow" data-toggle="modal"
                             data-target="#exampleModal"
                             @click="redirect(p.id)">
                        <i class="fa fa-edit my-1" aria-hidden="true"></i>
                     </button>
                     <button class="btn btn-sm btn-outline-danger rounded-pill shadow"
                             @click="getElementIdToDelete(p.id)"
                             data-target="#bitch" data-toggle="modal">
                        <i class="fa fa-trash " aria-hidden="true"></i>
                     </button>
                     <router-link :to="{name:'detailClient', params:{id:p.id}}"
                                  class="btn-outline-primary btn btn-sm rounded-pill shadow">
                        <i class="fa fa-list my-1" aria-hidden="true"></i>
                     </router-link>
                     <router-link :to="{name:'movementClient', params:{id:p.id}}"
                                  class="btn-outline-secondary btn btn-sm rounded-pill shadow">
                        <i class="fa fa-cash-register my-1" aria-hidden="true"></i>
                     </router-link>
                     <router-link :to="{name:'newPayment', params:{cmd:p.id}}"
                                  class="btn-outline-success btn btn-sm rounded-pill shadow">
                        <i class="fa fa-money-bill-alt my-1" aria-hidden="true"></i>
                     </router-link>
                  </td>
               </tr>
            </table-layout>
            <!--            <pagination :data="clients" align="center" @pagination-change-page="filter"></pagination>-->
         </div>
         <div class="col mt-0 segment">
            <h5 class="text-uppercase text-secondary">Filter par: </h5>
            <hr>
            <form @submit.prevent="">
               <div class="form-group  my-1">
                  <label for="">NOM ou Prénom</label>
                  <input type="text" class="form-control rounded-pill" v-model="searchTxt" v-on:keyup.enter="filter()"
                         placeholder="Recherche par Nom complet"
                         autofocus>
               </div>
               <div class="form-group   my-2">
                  <label for="">ID</label>
                  <input type="text" class="form-control rounded-pill" v-model="searchId"
                         v-on:keyup.enter="filter(1)"
                         placeholder="Recherche ID ...">
               </div>
               <!--               <hr>-->
               <!--               <button class="rounded-pill btn btn-outline-primary btn-block">FILTER</button>-->
            </form>
         </div>
      </div>


   </div>


</template>

<script>
    import ShowClient from "./showClient";
    import AlertModal from "@/components/Modals/AlertModal";
    import TableLayout from "@/components/layouts/TableLayout";
    // import pagination from "laravel-vue-pagination";
    import {HalfCircleSpinner} from 'epic-spinners'

    export default {
        name: "ClientsList",
        data() {
            return {
                ElementIdToDelete: null,
                clients: [],
                searchClients: null,
                searchTxt: '',
                searchId: '',
                by: 'id',
                opt: 'ste',
                client: {},
                dataTable: null,
                isloading: false
            }
        },
        mounted() {
            this.filter();
        },

        methods: {
            getElementIdToDelete(id) {
                this.ElementIdToDelete = id;
            },
            supp() {
                let id = this.ElementIdToDelete;
                axios.delete(`/api/clients/${id}/delete`)
                    .then(res => {
                        // this.$notification.error('produit bien supprimer')
                        // this.$router.push({name: 'listClient', params: {id}});
                        // this.getClients();
                        document.getElementById(this.ElementIdToDelete).remove()
                    })
                    .catch(err => {
                        this.$notification.error("Ce produit n'existe pas !")
                        this.$notification.error('impossible de supprimer ce produit')
                        // this.$router.push('/404')
                    })
            },
            redirect(id) {
                this.$router.push({name: 'updateClient', params: {id}});
            },
            filter(arg = 0) {
                this.isloading = true;
                let quote = this.searchTxt;
                let by = 'name';

                if (arg == 1) {
                    quote = this.searchId;
                    by = 'id'
                }

                axios.get(`/api/clients/by`,
                    {
                        params: {by, quote}
                    })
                    .then(({data}) => {
                        console.log(data)
                        this.clients = data;
                        this.isloading = false;

                    })
                    .catch(err => console.log(err.response))
            },
            EmptyText() {
                this.searchTxt = ''
                this.filter();
            },
            show(id) {
                this.$router.push({name: 'detailClient', params: {id}})
                // console.log(id)
                // const val = this.clients.data
                // this.client = _.find(val, o => {
                //     return o.id == id
                // });
            },
        },
        components: {
            TableLayout, HalfCircleSpinner,
            ShowClient, AlertModal,
        }
    }
</script>

<style>
   .pagination > li > a,
   .pagination > li > span {
      border: 1px solid #595c4f;
   }

   .pagination > li.active > a {
      background-color: #595c4f !important;
      color: #fff;
   }
</style>
