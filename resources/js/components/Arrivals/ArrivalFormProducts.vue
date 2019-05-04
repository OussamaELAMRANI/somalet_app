<template>
    <div id="arrival-form-products" class="segment">
        <form>
            <h3 class="text-uppercase text-center text-primary mb-3">Ajout des produits</h3>
            <div class="dropdown-divider"></div>
            <div class="row" v-for="(product,k) in products" :key="k">
                <div class="col input-group justify-content-lg-between">
                    <div class="form-group">
                        <label for="">Produit :</label>
                        <cool-select :items="prods"
                                     item-text="reference" item-value="id" name="produit"
                                     v-model="product.id" v-validate="'required'">
                            <template slot="item" slot-scope="{ item:p }" class="row">
                                <div class="row">
                                    <div class="col-12">
                                        <p>{{ p.name }} - {{ p.reference }}</p>
                                    </div>
                                    <div class="col-12">
                                        <img :src="getImg(p.img)" class="country-flag" style="width: 90px;height: 90px;"/>
                                    </div>
                                </div>
                            </template>
                        </cool-select>
                    </div>
                    <div class="form-group">
                        <label for="">Quantité :</label>
                        <input type="text" class="form-control" v-model="product.qte">
                    </div>
                    <div class="form-group">
                        <label for="">Fret douane :</label>
                        <input type="text" class="form-control" v-model="product.fret_douane">
                    </div>
                    <div class="form-group">
                        <label for="">Prix Unitaire HT :</label>
                        <input type="text" class="form-control" v-model="product.price_unit_ht">
                    </div>
                    <div class="form-group">
                        <label for="">Prix Unitaire TTC :</label>
                        <input type="text" class="form-control" v-model="product.price_unit_ttc">
                    </div>
                    <div class="form-group">
                        <i class="fas fa-minus-circle" @click="remove(k)" v-show="k || ( !k && products.length > 1)"></i>
                        <i class="fa fa-plus fa-3" @click="add(k)" v-show="k == products.length-1"></i>
                    </div>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
    import {CoolSelect} from 'vue-cool-select'

    export default {
        name: "ArrivalFormProduct",
        data() {
            return {
                products: [
                    {
                        id: '',
                        qte: null,
                        fret_douane: null,
                        price_unit_ht: null,
                        price_unit_ttc: null,
                    }
                ],
                prods: [],
            }
        },
        methods: {
            add(index) {
                this.products.push({name: ''});
            },
            remove(index) {
                this.products.splice(index, 1);
            },
            getImg(img) {
                try {
                    return `${process.env.MIX_APP_URL}/storage/${img}`;
                } catch (e) {
                    // return require("../assets/undefined.svg");
                }
            },
        },
        mounted() {
            axios.get('/api/products',
                {
                    params: {
                        fields: 'id,name,reference,img'
                    }
                }).then(res => {
                const v = res.data
                console.log(v)
                this.prods = v
            }).catch(err => console.log(err))
        },
        components: {
            CoolSelect
        }
    }
</script>
