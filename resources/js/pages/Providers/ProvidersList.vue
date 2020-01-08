<template>
   <div id="provider-list">
      <div class="row">
         <div class="col-3">
            <router-link class="btn-sm btn btn-primary" :to="{name:'newProvider'}">
               <i class="fa fa-plus mx-2"></i> Nouveau Fournisseur
            </router-link>
         </div>
      </div>

      <alert-modal title="Supperession d'un fournisseur" id="bitch">
         <template slot="content">
            <h5 class="text-uppercase text-danger text-center">
               Voullez-vous vraiment supprimer ce fournisseur ?
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
                  <h5 class="modal-title" id="exampleModalLabel">Detail d'un Fournisseur</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="alert segment">
                     <show-provider :provider="provider"/>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  <button type="button" class="btn btn-primary">Imprimer</button>
               </div>
            </div>
         </div>
      </div>


      <h3 class="text-lg-center text-secondary m-5">Liste des fournisseurs</h3>
      <div class="dropdown-divider"></div>

      <div class="row justify-content-around ">
         <h5 class="text-uppercase text-secondary m-3"> Parametre de rechercher </h5>
         <div class="col-8">
            <form @submit.prevent="">
               <div class="input-group m-3">
                  <div class="input-group-prepend">
                     <span class="input-group-text text-primary" id="inputGroup-sizing-sm">Filter Par :</span>
                     <div class="input-group-text bg-warning">
                        <input type="radio" name="opt-filter" value="name" v-model="opt"
                               v-on:change="EmptyText">
                        <span class="ml-1">Nom</span>
                     </div>
                     <div class="input-group-text bg-success text-white">
                        <input type="radio" name="opt-filter" value="ste" v-model="opt"
                               v-on:change="EmptyText">
                        <span class="ml-1">Societe</span>
                     </div>
                  </div>

                  <input type="text" class="form-control" v-model="searchTxt" v-on:keyup.enter="filter"
                         placeholder="Recherche par Societe"
                         autofocus>
                  <div class="input-group-append">
                     <button class="btn btn-outline-success " type="button" id="button-addon2">
                        <i class="fa fa-search" aria-hidden="true"></i>
                     </button>
                  </div>
               </div>
            </form>

         </div>
      </div>

      <table-layout :head-table="['#','Societe','TVA','Numero Siret','Nom','Actions']" :data="dataTable"
                    empty-text="Pas de fournisseur dans ce cas (Vide...)">

         <tr v-for="(p,index) in providers.data" :id="p.id">
            <th scope="row">{{index+1}}</th>
            <td>{{p.steName}}</td>
            <td>{{p.numTva}}</td>
            <td>{{p.nSiret}}</td>
            <td>{{p.firstName}} {{p.lastName}}</td>
            <td>
               <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal"
                       @click="detail(p.id)">
                  <i class="fa fa-list" aria-hidden="true"></i>
               </button>
               <button class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#exampleModal"
                       @click="show(p.id)">
                  <i class="fa fa-eye" aria-hidden="true"></i>
               </button>
               <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal"
                       @click="redirect(p.id)">
                  <i class="fa fa-edit" aria-hidden="true"></i>
               </button>
               <button class="btn btn-sm btn-danger"
                       @click="getElementIdToDelete(p.id)"
                       data-target="#bitch" data-toggle="modal">
                  <i class="fa fa-trash" aria-hidden="true"></i>
               </button>
            </td>
         </tr>
      </table-layout>
      <pagination :data="providers" align="center" @pagination-change-page="filter"></pagination>

   </div>


</template>

<script>
   import ShowProvider from "./showProvider";
   import AlertModal from "@/components/Modals/AlertModal";
   import TableLayout from "@/components/layouts/TableLayout";
   // import pagination from "laravel-vue-pagination";

   export default {
      name: "ProvidersList",
      data() {
         return {
            ElementIdToDelete: null,
            providers: {},
            searchProviders: null,
            searchTxt: '',
            opt: 'ste',
            provider: {},
            dataTable: null
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
            axios.delete(`/api/providers/${id}/delete`)
               .then(res => {
                  // this.$notification.error('produit bien supprimer')
                  // this.$router.push({name: 'listProvider', params: {id}});
                  // this.getProviders();
                  document.getElementById(this.ElementIdToDelete).remove()
               })
               .catch(err => {
                  this.$notification.error("Ce produit n'existe pas !")
                  this.$notification.error('impossible de supprimer ce produit')
                  // this.$router.push('/404')
               })
         },
         redirect(id) {
            this.$router.push({name: 'updateProvider', params: {id}});
         },
         filter(page = 1) {
            const by = this.opt

            axios.get(`/api/providers/search/${this.searchTxt}`,
               {
                  params: {
                     by,
                     page,
                  }
               })
               .then(res => {
                  const data = res.data
                  this.providers = data
                  this.dataTable = data.total
               })
               .catch(err => console.log(err.response))
         },
         EmptyText() {
            this.searchTxt = ''
            this.filter();
         },
         show(id) {
            console.log(id)
            const val = this.providers.data
            this.provider = _.find(val, o => {
               return o.id == id
            });
         },
         detail(id) {
            this.$router.push({name: 'detailProvider', params: {id}})
         },
      },
      components: {
         TableLayout,
         ShowProvider, AlertModal,
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
