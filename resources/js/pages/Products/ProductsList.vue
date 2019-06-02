<template>
    <div id="products_list" class="container">
        <alert-modal title="Supperession d'un Produit" id="bitch">
            <template slot="content">
                <h5 class="text-uppercase text-danger text-center">
                    Voullez-vous vraiment supprimer ce produit ?
                </h5>
            </template>
            <template slot="btn">
                <button class="btn btn-danger" @click="supp()"  data-dismiss="modal">Supprimer</button>
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
        <h3 class="text-lg-center text-secondary m-5">Liste des Produits</h3>
        <div class="dropdown-divider"></div>

        <div class="row justify-content-around ">
            <h5 class="text-uppercase text-secondary m-3"> Parametre de rechercher </h5>
            <div class="col-8">
                <form @submit.prevent="">
                    <div class="input-group m-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-primary" id="inputGroup-sizing-sm">Filter Par :</span>
                            <div class="input-group-text bg-warning">
                                <input type="radio" name="opt-filter" value="reference" v-model="opt"
                                       v-on:change="EmptyText">
                                <span class="ml-1">Reference</span>
                            </div>
                            <div class="input-group-text bg-success text-white">
                                <input type="radio" name="opt-filter" value="name" v-model="opt"
                                       v-on:change="EmptyText">
                                <span class="ml-1">Nom produit</span>
                            </div>
                        </div>

                        <input type="text" class="form-control" v-model="searchTxt" @input="filter"
                               placeholder="Recherche ..."
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


        <table class="table table-hover table-striped" v-if="products">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">image</th>
                <th scope="col">Reference</th>
                <th scope="col">Nom de Produit</th>
                <th scope="col">Description</th>
                <th scope="col">Remaques</th>
                <th scope="col">Couleur</th>
                <th scope="col">Unite</th>
                <th scope="col">Fournisseur</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="searchProduct === null">
                <td colspan="6">
                    <div class="row justify-content-center">
                        <div class="col-2">
                            <loading :animation-duration="6000" :size="40" color="#ff1d5e"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-else-if="searchProduct.length === 0">
                <td colspan="10">
                    <h6 class="text-center text-secondary m-5">Pas de Produit dans cette cas (Vide...) </h6>
                </td>
            </tr>
            <tr v-else v-for="(p,index) in searchProduct">
                <th scope="row">{{index+1}}</th>
                <!--                <td><img :src="'/storage/'+p.img" alt="image produit" height="60px"></td>-->
                <td><img :src="host+''+p.img" alt="image produit" height="60px"></td>
                <td>{{p.reference}}</td>
                <td>{{p.name}}</td>
                <td>{{p.description}}</td>
                <td>{{p.remark}}</td>
                <td>{{p.color.name}}</td>
                <td>{{p.unit.name}}</td>
                <td>{{p.provider.steName}}</td>
                <td>
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal"
                            @click="show(p.id)">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
                    <button  class="btn btn-sm btn-success"
                                 data-toggle="modal" data-target="#exampleModal"
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
            </tbody>
        </table>
    </div>
</template>

<script>

    import {SelfBuildingSquareSpinner} from 'epic-spinners'
    import AlertModal from "@/components/Modals/AlertModal";
    import ShowProduct from "./showProduct";

    export default {
        name: "ProductsList",
        data() {
            return {
                host:`${process.env.MIX_APP_URL}/storage/`,
                ElementIdToDelete:null,
                products: null,
                searchProduct: null,
                searchTxt: '',
                opt: 'reference',
                product: {}
            }
        },
        mounted() {
            axios.get('/api/products')
                .then(res => {
                    const data = res.data
                    this.products = data
                    this.searchProduct = data
                })
                .catch(err => console.log(err.response))
        },
        methods: {
            getElementIdToDelete(id){
                this.ElementIdToDelete = id;
            },
            supp(){
                let id = this.ElementIdToDelete;
                axios.delete(`/api/products/${id}/delete`)
                    .then(res => {
                        // this.$notification.error('produit bien supprimer')
                        // this.$router.push({name:'listProduct',params:{id}});
                        this.getProducts();
                    })
                    .catch(err => {
                        // this.$notification.error("Ce produit n'existe pas !")
                        // this.$notification.error('impossible de supprimer ce produit')
                        // this.$router.push('/404')
                    })
            },
            show(id) {
                // console.log(id)
                const val = this.products
                this.product = _.find(val, o => {
                    return o.id == id
                });
            },
            getProducts(){
                this.products = null;
                axios.get('/api/products')
                    .then(res => {
                        const data = res.data
                        this.products = data
                        this.searchProduct = data
                    })
                    .catch(err => console.log(err.response))
            },
            redirect(id){
                this.$router.push({name:'updateProduct',params:{id}});
            },
            filter() {
                const ste = this.searchTxt
                const val = this.products

                if (this.opt === 'reference')
                    this.searchProduct = _.filter(val, o => {
                        return _.startsWith(_.toUpper(o.reference), _.toUpper(ste))
                    });
                else
                    this.searchProduct = _.filter(val, o => {
                        return _.startsWith(_.toUpper(o.name), _.toUpper(ste))
                    });
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
            AlertModal,ShowProduct,
            'loading': SelfBuildingSquareSpinner
        }
    }
</script>

<style scoped>

</style>
