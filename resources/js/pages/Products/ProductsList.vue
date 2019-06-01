<template>
    <div id="products_list" class="container">
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


        <table class="table table-hover table-striped">
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
                <td><img :src="p.img" alt="image produit" height="60px"></td>
                <td>{{p.reference}}</td>
                <td>{{p.name}}</td>
                <td>{{p.description}}</td>
                <td>{{p.remark}}</td>
                <td>{{p.color.name}}</td>
                <td>{{p.unit.name}}</td>
                <td>{{p.provider.steName}}</td>
                <td>
                    <button  class="btn btn-sm btn-success"
                                 data-toggle="modal" data-target="#exampleModal"
                                 @click="redirect(p.id)">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-sm btn-danger">
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

    export default {
        name: "ProductsList",
        data() {
            return {
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
            'loading': SelfBuildingSquareSpinner
        }
    }
</script>

<style scoped>

</style>
