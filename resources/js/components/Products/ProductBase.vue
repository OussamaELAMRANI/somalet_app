<template lang="pug">
    #product_base
        transition( name="bounce")
            search-provider(@provider="getProvider" v-if='provider == null')
            div(v-else)
                button( class="btn btn-secondary btn-sm mb-5" @click="provider=null")
                    i(class="fas fa-arrow-left")
                    | Autre Fournisseur
                h5.text-success.mb-1 Nouveau produit du Fournissuer :
                    strong.text-dark.text-uppercase.ml-1 {{provider.steName}}
                .dropdown-divider
                .row.mb-2
                    .col-12
                        picture-input#art-portrait(width='200' height='200' margin='16' accept='image/*' size='10' ref='portraits'
                            button-class='btn btn-sm btn-info' :prefill='img_url'
                            :custom-strings="{upload: 'uploaded', drag: 'Ajouter une image du Portrait ...'}")
                        .dropdown-divider
                form(@submit.prevent='')
                    .form-group.segment
                        .row
                            .col
                                label(for='reference') Réference du produit
                                input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference')
                            .col
                                label(for='name') Nom du Produit
                                input#name.form-control(type='text' placeholder='SIRET ...' v-model='product.name')
                            .dropdown-divider

                        .row.align-items-center
                            .col
                                selected-colors(:new-color='newColors' @giveColors='getAllColors')
                            .col
                                color(@getColors='getColors')
                        select-unity(@sendUnity="getUnity")
                        .row
                            .col-6
                                label(for='stockAlerte') Alert quantit&eacute; minimum
                                input#stockAlerte.form-control(type='Number' placeholder='stockAlerte ...' v-model='product.alertQte')
                            .dropdown-divider
                            .col-6
                                label(for='type') Selectionnez le Type du produit
                                select#type.form-control(v-on:change='getType')
                                    option(selected='selected' disabled='disabled') Selectionnez...
                                    option(v-for=" t in ['MATIERE_PREMIERE','PRODUIT_FINI']" :selected='(t === type)')
                                        | {{t}}
                            // sub category
                        select-category(@sendSubCategory="getSubCategory")
                        .row
                            .col
                                label(for='desc') Description
                                textarea#desc.form-control(placeholder='Description du produit  ...' v-model='product.description')
                            .col
                                label(for='remark') Remarque
                                textarea#remark.form-control(placeholder='Remarque ...' v-model='product.remark')
                            .dropdown-divider

                        .row.mt-2
                            .col-2
                                button.btn.btn-success(@click='insert')
                                    i.fa.fa-save
                                    | Enregister

</template>

<script>
    import SearchProvider from "@/components/Provider/SearchProvider";
    import DynamicForm from "@/components/layouts/DynamicForm";
    import SelectedColors from "@/components/Colors/SelectedColors";
    import Color from "@/components/Colors/Colors";
    import SelectUnity from "@/components/layouts/SelectUnity";
    import SelectCategory from "@/components/layouts/SelectCategory";
    import PictureInput from "vue-picture-input";

    export default {
        name: "ProductBase",
        data() {
            return {
                provider: null,
                id: 0,
                type: 'MATIERE_PREMIERE',
                product: {
                    reference: '',
                    name: '',
                    alertQte: 0,
                    description: '',
                    remark: '',
                    unit_id: 1,
                    provider_id: '',
                    subcategory_id: '',
                    rapport: 0,
                },
                newColors: {},
                colorsId: [],
                img_url: '',
            }
        },
        methods: {
            getProvider(provider) {
                this.provider = provider
                this.product.provider_id = provider.id
            },
            getColors(data, hasColor) {
                if (!hasColor)
                    this.newColors = data.colors
            },
            getAllColors(data) {
                this.colorsId = data
            },

            getUnity(id, val) {
                this.product.unit_id = id;
                this.product.rapport = (val.indexOf('/') === -1) ? 0 : 1;
            },
            getSubCategory(val) {
                this.product.subcategory_id = val
            },
            getType(ev) {
                let val = ev.target.options[ev.target.options.selectedIndex].value
                this.type = val
            },
            insert() {

                const formData = new FormData()
                const images = this.$refs.portraits
                const colors = JSON.stringify(this.colorsId);
                const product = JSON.stringify(this.product);

                formData.append('img', images.file)
                formData.append('colors', colors)
                formData.append('product', product)

                axios.post('/api/products/new', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(({data}) => {
                    console.log(data)
                    this.$router.push({name: 'listProducts'})
                }).catch(err => console.log(err.response))
            }
        },
        components: {PictureInput, SelectCategory, SelectUnity, Color, SelectedColors, DynamicForm, SearchProvider},
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

    .fas {
        margin-right: 4px;
    }
</style>
