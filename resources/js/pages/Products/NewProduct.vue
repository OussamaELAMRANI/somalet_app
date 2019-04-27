<template lang="pug">
    #new-product.container
        transition(name="bounce")
            search-provider(@provider="getProvider" v-if="provider === null")
            div(v-else)
                button(@click="emptyProvider" class="btn btn-secondary btn-sm mb-5")
                    i.fas.fa-arrow-left
                    | Selection un autre fournisseur
                h5.text-success.mb-1
                    | Nouveau produit  du Fournissuer :
                    strong.text-dark " {{provider.steName}} "
                .dropdown-divider
                .row
                    .col
                        picture-input#art-portrait(width="200" height="200" margin="16" accept="image/*" size="10" ref="portraits" buttonClass="btn btn-sm btn-info"
                            :customStrings="{upload: '<h1>Bummer!</h1>', drag: 'Ajouter une image du Portrait ...'}")
                        .dropdown-divider
                form(@submit.prevent="")
                    .form-group.segment
                        .row
                            .col
                                label(for="reference") Reference du produit
                                input( type="text" class="form-control " id="reference" placeholder="Ref ..." v-model="refr")
                            .col
                                label(for="name") Nom Produit
                                input( type="text" class="form-control " id="name"  placeholder="SIRET ..." v-model="name")
                            .dropdown-divider
                        .row
                            .col
                                label(for="desc") Description
                                textarea(  class="form-control " id="desc"  placeholder="Description du produit  ..." v-model="desc")
                            .col
                                label(for="note") Remarque
                                textarea( class="form-control " id="note" placeholder="Remarque ..." v-model="note")
                            .dropdown-divider
                        .row
                            .col
                                label(for='inputState') Selectionnez une couleur
                                select#inputState.form-control(v-on:change="getColor")
                                    option(selected disabled) Selectionnez...
                                    option NOUVEAU ++
                                    option(v-for=' c in colors' :id="'color_'+c.id") {{ c.name }}

                            .col( v-if="newColor === true")
                                label(for="name") Nouvelle Couleur
                                input( type="text" class="form-control " id="new-color"  placeholder="Couleur ..."
                                    v-model="color" v-on:keyup.enter="addColor")
                            .col
                                label(for='inputState') Selectionnez une unite
                                select#unity.form-control(v-on:change="getUnity")
                                    option(selected disabled) Selectionnez...
                                    option NOUVEAU ++
                                    option(v-for=' c in unities' :id="'unity_'+c.id") {{ c.name }}

                            .col(v-if="newUnity")
                                label(for="name") Nouvelle Unite
                                input( type="text" class="form-control " id="new-unity"  placeholder="Unite ..." v-model="unity"
                                    v-on:keyup.enter="addUnity")
                        .dropdown-divider.m-3
                        .row
                            .col
                                label(for='inputState') Selectionnez une unite
                                select#type.form-control(v-on:change="getType")
                                    option(selected disabled) Selectionnez...
                                    option( v-for=" t in ['MATIERE_PREMIERE','PRODUIT_FINI']") {{t}}
                            .col
                                button.btn.btn-success.float-right(@click="insert")
                                    i.fa.fa-save
                                    | Enregister

</template>

<script>
    import SearchProvider from "../../components/Provider/SearchProvider";
    import PictureInput from "vue-picture-input";

    export default {
        name: "NewProduct",
        data() {
            return {
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
            }
        },
        methods: {
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
            getUnity(ev) {
                const val = ev.target.value
                let id = ev.target.options[ev.target.options.selectedIndex].id
                id = id.split('_')
                if (val === 'NOUVEAU ++') {
                    this.unity = null
                    this.newUnity = true
                } else {
                    this.newUnity = false
                    this.unity = parseInt(id[1])
                }
            },
            getType(ev) {
                let val = ev.target.options[ev.target.options.selectedIndex].value
                this.type = val
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
                formData.append('reference', this.refr)
                formData.append('name', this.name)
                formData.append('description', this.desc)
                formData.append('remark', this.note)
                formData.append('color_id', this.color);
                formData.append('unit_id', this.unity)
                formData.append('type', this.type)
                formData.append('provider_id', this.provider.id)


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
            }),
                axios.get('/api/unities').then(res => {
                    this.unities = res.data
                })
        }
        ,
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
