<template>
    <div id="arrival-form-products" class="segment">
        <form @submit.prevent="sendProduct">
            <h3 class="text-uppercase text-center text-primary mb-3">Ajout des produits</h3>
            <div class="dropdown-divider"></div>
            <div class="container" v-for="(product,k) in products" :key="k">
                <div class="row">
                    <div class="col-12 input-group justify-content-lg-between">
                        <div class="form-group">
                            <label for="">Produit :</label>
                            <cool-select :items="prods[k]" @search="onSearch($event,k)" :loading="loading"
                                         item-text="reference" item-value="id" :name="'produit'+k"
                                         v-model="product.id" v-validate="'required'" :ref="k">
                                <template slot="item" slot-scope="{ item:p }" class="row">
                                    <div class="row">
                                        <div class="col-12">
                                            <p>{{ p.name }} - {{ p.reference }}</p>
                                        </div>
                                        <div class="col-12">
                                            <img :src="getImg(p.img)" class="country-flag"
                                                 style="width: 90px;height: 90px;"/>
                                        </div>
                                    </div>
                                </template>
                                <template slot="no-data">
                                    {{
                                    noData
                                    ? "aucun produit trouvée"
                                    : "Taper au moins deux caractère pour effectuer la recherche."
                                    }}
                                </template>
                            </cool-select>
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('produit'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('produit'+k)">{{ errors.first('produit'+k) }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group">
                            <label for="">Quantité :</label>
                            <input type="number" :name="'qte'+k" class="form-control" v-model="product.qte"
                                   v-validate="'required'">
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('qte'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('qte')">{{ errors.first('qte') }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group" v-if="type === 'INTERNATIONAL'">
                            <label for="">Prix en devise :</label>
                            <input type="number" class="form-control" v-model="product.price_devise"
                                   :name="'price_devise'+k"
                                   v-validate="{required:true}">
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('price_devise'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('prix_u_ttc')">{{ errors.first('prix_u_ttc') }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group" v-if="type === 'INTERNATIONAL'">
                            <label for="">Fret douane :</label>
                            <input type="number" class="form-control" v-model="product.fret_douane" :name="'douane'+k"
                                   v-validate="'required'">
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('douane'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('douane')">{{ errors.first('douane') }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group" v-if="type !== 'INTERNATIONAL'">
                            <label for="">Prix Unitaire HT :</label>
                            <input type="number" :name="'prix_u_HT'+k" class="form-control"
                                   v-model="product.price_unit_ht"
                                   v-validate="'required'">
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('prix_u_HT'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('prix_u_HT')">{{ errors.first('prix_u_HT') }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group" v-if="type !== 'INTERNATIONAL'">
                            <label for="">Prix Unitaire TTC :</label>
                            <input type="number" class="form-control" v-model="product.price_unit_ttc"
                                   :name="'prix_u_ttc'+k"
                                   v-validate="{ min_value: product.price_unit_ht , required:true}">
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('prix_u_ttc'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('prix_u_ttc')">{{ errors.first('prix_u_ttc') }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group" v-if="type === 'INTERNATIONAL'">
                            <label for="">Ct de revient Unitaire :</label>
                            <input type="number" class="form-control" v-model="product.prix_achat"
                                   :name="'prix_achat'+k"
                                   v-validate="{required:true}" disabled>
                            <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('prix_achat'+k)"></i>
                            <!--                        <span class="help text-danger" v-show="errors.has('prix_u_ttc')">{{ errors.first('prix_u_ttc') }}</span>-->
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group">
                            <label for="">Remarque :</label>
                            <textarea class="form-control rounded-0" :name="'remark'+k"
                                      v-model="product.remark"></textarea>
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-danger" @click="remove(k)"
                                    v-show="k || ( !k && products.length > 1)">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                            <button class="btn btn-sm btn-success" @click="add(k)" v-show="k == products.length-1">
                                <i class="fa fa-plus fa-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="errors">
                    <span class="help text-danger col-12" v-show="errors.has('produit'+k)">{{ errors.first('produit'+k) }}</span>
                    <span class="help text-danger col-12"
                          v-show="errors.has('qte'+k)">{{ errors.first('qte'+k) }}</span>
                    <span class="help text-danger col-12"
                          v-show="errors.has('douane'+k)">{{ errors.first('douane'+k) }}</span>
                    <span class="help text-danger col-12" v-show="errors.has('prix_u_HT'+k)">{{ errors.first('prix_u_HT'+k) }}</span>
                    <span class="help text-danger col-12" v-show="errors.has('prix_u_ttc'+k)">{{ errors.first('prix_u_ttc'+k) }}</span>
                    <div class="dropdown-divider"></div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {CoolSelect} from 'vue-cool-select'

    export default {
        name: "ArrivalFormProduct",
        props: ['type', 'arrival'],
        data() {
            return {
                messageErr: 'Champs obligatoire !',
                products: [
                    {
                        id: '',
                        qte: null,
                        fret_douane: null,
                        price_unit_ht: null,
                        price_unit_ttc: null,
                        price_devise: null,
                        remark: null,
                        prix_dh: null,
                        transitaire: null,
                        transport: null,
                        magazinage: null,
                        surestaries: null,
                        manutension: null,
                        fret: null,
                        autres: null,
                        cout_global: null,
                        prix_achat: null,
                        prix_vente: null,
                    }
                ],
                prods: [
                    []
                ],
                loading: false,
                noData: false,
                selectedProductsId: [],
            }
        },
        created: function () {
            this.type = 'INTERNATIONAL';
        },
        methods: {
            add() {
                this.prods.push([]);
                this.products.push({name: ''});
            },
            remove(index) {
                this.products.splice(index, 1);
                this.prods[index] = [];
            },
            getImg(img) {
                try {
                    return `${process.env.MIX_APP_URL}/storage/${img}`;
                } catch (e) {
                    // return require("../assets/undefined.svg");
                }
            },
            sendProduct() {
                const data = new FormData()

                data.append('products', this.products)

                axios.post('/api/arrivals/products', data)
                    .then(({data}) => console.log(data))
                    .catch(err => console.log(err))
            },
            onSearch(search, id) {
                let SelectedIds = this.arrayRemove(this.getProductsSelectedId(), null);
                const lettersLimit = 2;
                this.noData = false;
                if (search.length < lettersLimit) {
                    this.prods[id] = [];
                    this.loading = false;
                    return;
                }
                this.loading = true;

                axios.get('/api/search/products', {
                    params: {
                        products: search
                    }
                })
                    .then(({data}) => {
                        let nvData = data;
                        if (SelectedIds.length === data.length) {
                            // alert('vidange')
                            nvData = [];
                        } else {
                            for (let p = 0; p < data.length; p++) {
                                let prodId = data[p].id;
                                if (SelectedIds.includes(prodId) && !isNaN(prodId)) {
                                    nvData = this.removeElementArray(nvData, prodId);
                                    p--;
                                }
                            }
                        }
                        this.prods[id] = nvData;
                        this.loading = false;
                        if (this.prods[id].length === 0) this.noData = true;
                    })
            },
            validateBeforeSubmit() {
                this.calculerCtRevient(this.arrival);
                return this.$validator.validateAll().then((result) => {
                    if (result) this.$emit('getProducts', this.products);
                    return result;
                });
            },
            // obj => product object
            getProductsSelectedId() {
                this.selectedProductsId = [];
                for (let p in this.products) {
                    if (!isNaN(this.products[p].id)) {
                        this.selectedProductsId.push(this.products[p].id);
                    }
                }
                console.log(this.selectedProductsId);
                return this.selectedProductsId;
            },
            removeElementArray(tab, id) {
                for (let i = 0; i < tab.length; i++) {
                    if (tab[i].id === id) {
                        tab.splice(i, 1);
                        i--;
                    }
                }
                return tab;
            },
            arrayRemove(arr, value) {
                return arr.filter(function (ele) {
                    return ele != value;
                });
            },
            // a => arrival from props
            calculerCtRevient(a) {
                console.log(a);
                for (let p in this.products) {
                    let prod = this.products[p];
                    if (!isNaN(prod.id) && !isNaN(prod.qte) && !isNaN(prod.fret_douane) && !isNaN(prod.price_devise)) {
                        let priceDh = parseFloat(prod.qte) * parseFloat(a.cours_change);
                        priceDh = this.round(priceDh, 2);
                        alert('pricedh' + priceDh);
                        //price du produit en devise / prix du prix total en devise
                        let c = parseFloat(prod.price_devise) / parseFloat(a.price_devise);
                        alert('c' + c);
                        //transitaire
                        prod.transitaire = this.round(parseFloat(a.price_devise) * c, 2);
                        alert('transitaire' + prod.transitaire);
                        //transport
                        prod.transport = this.round(parseFloat(a.transport) * c, 2);
                        alert('transitaire' + prod.transport);

                        //magazinage
                        prod.magazinage = this.round(parseFloat(a.magazinage) * c, 2);
                        alert('magazinage' + prod.magazinage);

                        //surestaries
                        prod.surestaries = this.round(parseFloat(a.surestaries) * c, 2);
                        alert('surestaries' + prod.surestaries);

                        //manutention
                        prod.manutension = this.round(parseFloat(a.manutension) * c, 2);
                        alert('manutension' + prod.manutension);


                        //fret
                        prod.fret = this.round(parseFloat(a.fret) * c, 2);
                        alert('fret' + prod.fret);

                        //autres
                        prod.autres = this.round(parseFloat(a.autres) * c, 2);
                        alert('autres' + prod.autres);

                        //cout global
                        prod.cout_global = this.value(priceDh) + this.value(prod.transitaire) + this.value(prod.transport) + this.value(prod.magazinage) + this.value(prod.fret_douane) + this.value(prod.surestaries) + this.value(prod.manutension) + this.value(prod.fret) + this.value(prod.autres);
                        prod.cout_global = this.round(prod.cout_global, 2);
                        alert('cout global'+prod.cout_global);
                        //calcule de cout de revient ou d'achat
                        prod.prix_achat = prod.cout_global / prod.qte;
                        prod.prix_achat = this.round(prod.prix_achat, 2);
                        alert(prod.prix_achat);
                    }
                }
            },
            round(value, decimals) {
                return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
            },
            value(v) {
                if (isNaN(v)) {
                    return 0;
                } else {
                    return Number(v);
                }
            }
        },
        components: {
            CoolSelect
        }
    }
</script>
