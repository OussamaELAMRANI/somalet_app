<template>
   <div id="products_list" class="container">
<!--      <router-link :to="{name:'newProduct'}" class="btn-sm btn-success btn text-capitalize">-->
<!--         <i class="fa fa-plus mx-1"></i>-->
<!--         Ajouter nouveau produit-->
<!--      </router-link>-->

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
            <!--                <td><img :src="p.img" alt="image produit" height="60px"></td>-->
            <td class="align-middle">{{p.reference}}</td>
            <td class="align-middle">{{p.name}}</td>
            <td :class="(p.color)?'row justify-content-center b-0 m-0':'' ">
               <div v-if="p.color" class="row justify-content-around">
                  <div class="col-2 rounded-circle" :style="{backgroundColor: p.color.color}"></div>
                  <div class="col">{{p.color.name}}</div>
               </div>
               <template v-else>
                  SANS COULEUR
               </template>
            </td>
            <td class="align-middle">{{p.unit.name}}</td>
            <td class="align-middle">{{p.provider.steName}}</td>
            <td style="width: 130px">
               <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal"
                       @click="show(p.id)">
                  <i class="fa fa-eye" aria-hidden="true"></i>
               </button>
               <button class="btn btn-sm btn-primary"
                       @click="redirect(p.id)">
                  <i class="fa fa-list" aria-hidden="true"></i>
               </button>
               <button class="btn btn-sm btn-danger"
                       @click="getElementIdToDelete(p.id)"
                       data-target="#bitch" data-toggle="modal">
                  <i class="fa fa-trash" aria-hidden="true"></i>
               </button>
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
   import ComboBox from "@/components/layouts/ComboBox";

   export default {
      name: "ProductsList",
      data() {
         return {
            // host: `${process.env.MIX_APP_URL}/storage/`,
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
            tableDate: null
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
            axios.get(`/api/products/search/${search}?by=${by}&with=unit,color,provider&page=${page}`)
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
         // show(id) {
         //     console.log(id)
         //     const val = this.providers
         //     this.provider = _.find(val, o => {
         //         return o.id == id
         //     });
         // }
      },
      components: {
         ComboBox,
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
