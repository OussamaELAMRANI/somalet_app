<template>
   <div id="products_list" class="container">

      <alert-modal title="Supperession d'un Produit" id="bitch">
         <template slot="content">
            <h5 class="text-uppercase text-danger text-center">
               Voullez-vous vraiment supprimer ce produit ?
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
                  <h5 class="modal-title" id="exampleModalLabel">Detail d'un Produit</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="alert segment">
                     <show-product :product="product"/>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  <button type="button" class="btn btn-primary">Imprimer</button>
               </div>
            </div>
         </div>
      </div>

      <h3 class="text-lg-center text-secondary m-4">Liste des Produits</h3>
      <div class="dropdown-divider"></div>

      <div class="row justify-content-end align-middle">
         <div class="col-12">
            <h5 class="text-uppercase text-secondary m-3"> Paramétre de rechercher </h5>
         </div>
         <div class="col-2 mt-3">
            <router-link :to="{name:'newProduct'}" class="btn btn-outline-success  text-center rounded-pill">
               <i class="fa fa-plus mx-1"></i> Ajouter Produit
            </router-link>
         </div>
         <div class="col-10">
            <form @submit.prevent="">
               <div class="input-group my-3">
                  <div class="input-group-prepend">
                     <span class="input-group-text text-primary" id="inputGroup-sizing-sm">Filter Par :</span>
                  </div>
                  <select class="custom-select " id="inputGroupSelect01" v-model="filterBy">
                     <option selected disabled>Filter Par ...</option>
                     <option value="reference">Réference</option>
                     <option value="name">Désignation</option>
                     <option value="provider">Fournisseur</option>
                     <option value="color">Couleur</option>
                     <option value="unit">Unité</option>
                  </select>
                  <input type="text" class="form-control" v-model="searchTxt" @input="filter"
                         placeholder="Recherche ..." autofocus>
                  <div class="input-group-append">
                     <button class="btn btn-outline-success " type="button" id="button-addon2">
                        <i class="fa fa-search" aria-hidden="true"></i>
                     </button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <hr>
      <table-layout
         :head-table="['#','image','Reference',	'Nom de Produit','Couleur',	'Unite','Fournisseur','Actions']"
         empty-text="Pas de Produit dans ce cas (Vide...)"
         :data="tableDate">
         <tr v-for="(p,index) in products.data">
            <th scope="row">{{index+1}}</th>
            <td class="align-middle"><img :src="getImage(p.img)" alt="image produit" height="60px"></td>
            <td class="align-middle">{{p.reference}}</td>
            <td class="align-middle">{{p.name}}</td>
            <td class="align-middle">
               <template v-if="p.color">
                  <div :style="{backgroundColor: p.color.color, height:'30px',width:'100%'}"
                       class="shadow rounded"></div>
               </template>
               <template v-else>
                  SANS COULEUR
               </template>
            </td>
            <td class="align-middle">{{p.unit.name}}</td>
            <td class="align-middle">{{p.provider.steName}}</td>
            <td style="width: 100px">
               <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal"
                       @click="show(p.id)">
                  <i class="fa fa-eye"></i>
               </button>
               <button class="btn btn-sm btn-primary"
                       @click="redirect(p.id)">
                  <i class="fa fa-list"></i>
               </button>
               <button class="btn btn-sm btn-danger" v-if="roles.includes(['ADMINE']) || roles.includes(['COMMERCIAL']) "
                       @click="getElementIdToDelete(p.id)"
                       data-target="#bitch" data-toggle="modal">
                  <i class="fa fa-trash"></i>
               </button>
               <router-link :to="{name:'updateProduct', params:{id:p.id}}" class="btn btn-sm btn-info">
                  <i class="fa fa-pen text-white"></i>
               </router-link>
            </td>
         </tr>

      </table-layout>
      <pagination :data="products" align="center" @pagination-change-page="filter"></pagination>

   </div>
</template>

<script>

   import {SelfBuildingSquareSpinner} from 'epic-spinners'
   import AlertModal from "@/components/Modals/AlertModal";
   import ShowProduct from "./showProduct";
   import TableLayout from "@/components/layouts/TableLayout";
   import ModalDetail from "@/components/Modals/ModalDetail";
   import BigTitle from "@/components/layouts/BigTitle";
   import store from "@/store";

   export default {
      name: "ProductsList",
      data() {
         return {
            filterBy: 'reference',
            ElementIdToDelete: null,
            products: {},
            searchProduct: null,
            searchTxt: '',
            opt: 'reference',
            product: {
               img: '',
               provider: {
                  steName: ''
               },
               reference: '',
               name: '',
               alertQte: '',
               description: '',
               remark: '',
               color: {color: ''},
               unit: {name: ''},
            },
            tableDate: null,
            roles: store.getters.roles

         }
      },
      mounted() {
         this.filter()
      },
      methods: {
         getImage(img) {
            return `/storage/${img}`
         },
         getElementIdToDelete(id) {
            this.ElementIdToDelete = id;
         },
         supp() {
            let id = this.ElementIdToDelete;
            axios.delete(`/api/products/${id}/delete`)
               .then(res => {
                  this.getProducts();
               })
               .catch(err => {
                  this.$notification.error("Ce produit n'existe pas !")
               })
         },
         show(id) {
            // console.log(id)
            const val = this.products.data
            this.product = _.find(val, o => {
               return o.id == id
            });
         },
         getProducts() {
            this.products = null;
            axios.get('/api/products')
               .then(res => {
                  const data = res.data
                  this.products = data
                  this.searchProduct = data
               })
               .catch(err => console.log(err.response))
         },
         redirect(id) {
            this.$router.push({name: 'detailProduct', params: {id}});
         },
         filter(page = 1) {
            const by = this.filterBy
            const search = this.searchTxt
            axios.get(`/api/products/search`,
               {params:{by, with: 'unit,color,provider', page, value: search }})
               .then(res => {
                  const data = res.data
                  this.products = data
                  this.tableDate = data.total
               })
               .catch(err => console.log(err.response))
         },
         EmptyText() {
            this.searchTxt = ''
         },
      },
      components: {
         BigTitle,
         ModalDetail,
         TableLayout,
         AlertModal, ShowProduct,
         'loading': SelfBuildingSquareSpinner
      }
   }
</script>

<style scoped>
   .rounded-circle {
      height: 30px;
      width: 20px;
      box-shadow: 0 1px 1px 0 rgba(60, 64, 67, .08), 0 1px 3px 1px rgba(60, 64, 67, .16);
      margin: 0 6px;
   }

</style>
