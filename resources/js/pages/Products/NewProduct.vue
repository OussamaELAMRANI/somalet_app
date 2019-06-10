<template>
    <div class="container" id="new-product">
        <transition name="bounce">
            <search-provider @provider="getProvider" v-if="provider === null"></search-provider>
            <div v-else="v-else">
                <button class="btn btn-secondary btn-sm mb-5" @click="emptyProvider"><i class="fas fa-arrow-left"></i>Selection
                    un autre fournisseur
                </button>
                <h5 class="text-success mb-1">Nouveau produit du Fournissuer :<strong class="text-dark">"
                    {{provider.steName}} "</strong></h5>
                <div class="dropdown-divider"></div>
                <div class="row">
                    <div class="col">
                        <picture-input id="art-portrait" width="200" height="200" margin="16" accept="image/*" size="10"
                                       ref="portraits" buttonClass="btn btn-sm btn-info" :prefill="img_url"
                                       :customStrings="{upload: '&lt;h1&gt;Bummer!&lt;/h1&gt;', drag: 'Ajouter une image du Portrait ...'}"></picture-input>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <form @submit.prevent="">
                    <div class="form-group segment">
                        <div class="row">
                            <div class="col"><label for="reference">Reference du produit</label><input
                                class="form-control " type="text" id="reference" placeholder="Ref ..." v-model="refr"
                                :disabled="id!==0"/></div>
                            <div class="col"><label for="name">Nom du Produit</label><input class="form-control "
                                                                                            type="text" id="name"
                                                                                            placeholder="SIRET ..."
                                                                                            v-model="name"/></div>
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="row">
                            <div class="col"><label for="desc">Description</label><textarea class="form-control "
                                                                                            id="desc"
                                                                                            placeholder="Description du produit  ..."
                                                                                            v-model="desc"></textarea>
                            </div>
                            <div class="col"><label for="note">Remarque</label><textarea class="form-control " id="note"
                                                                                         placeholder="Remarque ..."
                                                                                         v-model="note"></textarea>
                            </div>
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="row">
                            <div class="col"><label for="inputState">Selectionnez une couleur</label><select
                                class="form-control" id="inputState" v-on:change="getColor">
                                <option selected="selected" disabled="disabled">Selectionnez...</option>
                                <option>NOUVEAU ++</option>
                                <option v-for=" c in colors" :id="'color_'+c.id" :selected="(c.id == color)">{{ c.name
                                    }}
                                </option>
                            </select></div>
                            <div class="col" v-if="newColor === true"><label for="name">Nouvelle Couleur</label><input
                                class="form-control " type="text" id="new-color" placeholder="Couleur ..."
                                v-model="color" v-on:keyup.enter="addColor"/></div>
                            <div class="col"><label for="inputState">Selectionnez l'unité</label><select
                                class="form-control" id="unity" v-on:change="getUnity">
                                <option selected="selected" disabled="disabled">Selectionnez...</option>
                                <option>NOUVEAU ++</option>
                                <option v-for=" c in unities" :id="'unity_'+c.id" :selected="(c.id == unity)">{{ c.name
                                    }}
                                </option>
                            </select></div>
                            <div class="col" v-if="newUnity"><label for="name">Nouvelle Unite</label><input
                                class="form-control " type="text" id="new-unity" placeholder="Unite ..." v-model="unity"
                                v-on:keyup.enter="addUnity"/>
                            </div>
                            <div class="col" v-if="hasRapport">
                                <label for="rapport">{{ labelRapport }}</label>
                                <input class="form-control " type="text" id="rapport" :placeholder="labelRapport"
                                       v-model="rapport"/>
                            </div>
                        </div>
                        <div class="dropdown-divider m-3"></div>
                        <div class="row">
                            <div class="col"><label for="stockAlerte">Alert quantité minimum</label><input
                                class="form-control " type="Number" id="stockAlerte" placeholder="stockAlerte ..."
                                v-model="alertQte"/></div>
                            <div class="dropdown-divider"></div>
                        </div>
                        <div class="row">
                            <div class="col-12"><label for="inputState">Selectionnez le Type du produit</label><select
                                class="form-control" id="type" v-on:change="getType">
                                <option selected="selected" disabled="disabled">Selectionnez...</option>
                                <option v-for=" t in ['MATIERE_PREMIERE','PRODUIT_FINI']" :selected="(t === type)">
                                    {{t}}
                                </option>
                            </select>
                            </div>
                            <div class="col-12"><label for="inputState">Selectionnez la categorie</label>
                                <select
                                    class="form-control" id="category" v-on:change="getCategory">
                                    <option selected="selected" disabled="disabled">Selectionnez...</option>
                                    <template v-for="t in categories">
                                        <optgroup :label="t.category_name">
                                            <option v-for="sub in t.sub_categories" :id="'subcategory_'+sub.id"
                                                    :selected="(sub.id == subcategory_id)">
                                                {{sub.sub_category}}
                                            </option>
                                        </optgroup>
                                    </template>
                                </select>
                            </div>
                            <div class="col">
                                <button class="btn btn-success float-right m-4" @click="insert"><i
                                    class="fa fa-save"></i>Enregister
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </transition>
    </div>
</template>

<script>
    import SearchProvider from "../../components/Provider/SearchProvider";
    import PictureInput from "vue-picture-input";

    export default {
        name: "NewProduct",
        data() {
            return {
                img_url: null,
                provider: null,
                name: '',
                refr: '',
                desc: '',
                note: '',
                color: '',
                colors: '',
                unity: '',
                unities: '',
                newUnity: false,
                newColor: false,
                type: '',
                alertQte: 0,
                id: 0,
                img: '',
                isNew: true,
                categories: null,
                subcategory_id: null,
                newSubCategory: false,
                hasRapport: false,
                rapport: null,
                labelRapport: "Rapport"
            }
        },
        methods: {
            getImg(img) {
                try {
                    return `${process.env.MIX_APP_URL}/storage/${img}`;
                } catch (e) {
                    // return require("../assets/undefined.svg");
                }
            },
            getProvider(p) {
                this.provider = p
            },
            emptyProvider() {
                this.provider = null
            },
            getColor(ev) {
                const val = ev.target.value
                let id = ev.target.options[ev.target.options.selectedIndex].id
                id = id.split('_')
                if (val === 'NOUVEAU ++') {
                    this.color = null
                    this.newColor = true
                } else {
                    this.newColor = false
                    this.color = parseInt(id[1])
                }
            },
            setRapport(val){
                if (val === "Mètre") {
                    this.labelRapport = "Mètre/Rouleau";
                    this.hasRapport = true;
                } else if (val === "Kg (rouleau)") {
                    this.labelRapport = "Kg/Rouleau";
                    this.hasRapport = true;
                } else if (val === "Box") {
                    this.labelRapport = "Pièce/Box";
                    this.hasRapport = true;
                } else {
                    this.hasRapport = false;
                    this.rapport = 0;
                }
            },
            getUnity(ev) {
                let val = ev.target.value
                let id = ev.target.options[ev.target.options.selectedIndex].id
                id = id.split('_')
                if (val === 'NOUVEAU ++') {
                    this.unity = null
                    this.newUnity = true
                } else {
                    this.newUnity = false
                    this.unity = parseInt(id[1])
                }
                this.setRapport(val);
            },
            getType(ev) {
                let val = ev.target.options[ev.target.options.selectedIndex].value
                this.type = val
            },
            getCategory(ev) {
                let val = ev.target.options[ev.target.options.selectedIndex].value
                let id = ev.target.options[ev.target.options.selectedIndex].id
                id = id.split('_')
                if (val === 'NOUVEAU ++') {
                    this.subcategory_id = null
                    this.newSubCategory = true
                } else {
                    this.newSubCategory = false
                    this.subcategory_id = parseInt(id[1])
                }
            },
            addColor() {
                const _this = this
                axios.post('/api/colors',
                    {
                        name: this.color
                    }
                ).then(function (response) {
                    _this.colors = []
                    _this.colors = response.data.colors
                    _this.newColor = false;
                    _this.color = ''
                })
                    .catch(function (error) {
                        console.log(error.response);
                    })
            },
            addUnity() {
                const _this = this
                axios.post('/api/unities',
                    {
                        name: this.unity
                    }
                ).then(function (response) {
                    _this.unities = []
                    _this.unities = response.data.unities
                    _this.newUnity = false;
                    _this.unity = ''
                })
                    .catch(function (error) {
                        console.log(error.response);
                    })
            },
            insert() {
                const formData = new FormData()
                const images = this.$refs.portraits

                formData.append('img', images.file)

                formData.append('id', this.id)

                formData.append('reference', this.refr)
                formData.append('name', this.name)
                formData.append('description', this.desc)
                formData.append('remark', this.note)
                formData.append('color_id', this.color);
                formData.append('unit_id', this.unity)
                formData.append('type', this.type)
                formData.append('provider_id', this.provider.id)
                formData.append('alertQte', this.alertQte)
                formData.append('subcategory_id', this.subcategory_id)
                formData.append('rapport', this.rapport)

                axios.post('/api/products', formData)
                    .then(res => {
                        console.log(res.data)
                        this.$router.push({name: 'listProducts'})
                    })
                    .catch(err => console.log(err.response))
            },

        },
        mounted() {
            axios.get('/api/colors').then(res => {
                this.colors = res.data
            })
            axios.get('/api/unities').then(res => {
                this.unities = res.data
            })
            axios.get('/api/categories').then(res => {
                this.categories = res.data
            })
            //pour determiner si l'etat de la vue en mode : Ajout ou modification
            //si id == null => Ajout
            //si id != null => Modification
            const id = this.$route.params['id'];
            if (this.$route.params['id'] !== undefined) {
                this.isNew = false
                this.id = id
                axios.get(`/api/products/${id}`)
                    .then(res => {
                        const data = res.data
                        this.provider = data.provider
                        this.name = data.name
                        this.refr = data.reference
                        this.desc = data.description
                        this.note = data.remark
                        this.color = data.color.id
                        // this.colors = data.colors
                        this.unity = data.unit.id
                        // this.unities = data.unities
                        this.type = data.type
                        this.alertQte = data.alertQte
                        this.id = data.id
                        this.rapport = data.rapport
                        this.setRapport(data.unit.name)
                        this.img = data.img
                        this.subcategory_id = data.subcategory.id
                        this.img_url = `${process.env.MIX_APP_URL}/storage/${this.img}`
                        // this.$refs.portraits.file = data.img
                    })
                    .catch(err => {
                        // this.$notification.error("Ce produit n'existe pas !")
                        this.$notification.error(err)
                        // this.$router.push('/404')
                    })

            } else {
                this.provider = null;
                this.isNew = true
                this.id = 0
            }
        },
        destroyed() {
            console.log('is destoryed')
        },
        components: {
            PictureInput, SearchProvider
        }
    }
</script>

<style scoped>
    .bounce-enter-active {
        animation: bounce-in .5s;
    }

    .bounce-leave-active {
        animation: bounce-in .5s reverse;
    }

    @keyframes bounce-in {
        0% {
            transform: scale(.4);
        }
        100% {
            transform: scale(1);
        }
    }
</style>
