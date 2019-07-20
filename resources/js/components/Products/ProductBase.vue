<template lang="pug">
    #product_base
        transition( name="bounce")
            search-provider(@provider="getProvider" v-if='provider == null')
            div(v-else)
                button( class="btn btn-secondary btn-sm mb-5" @click="provider=null")
                    i(class="fas fa-arrow-left")
                    | Autre Fournisseur
                form(@submit.prevent='')
                    .form-group.segment
                        .row
                            .col
                                label(for='reference') Réference du produit
                                input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference' :disabled='id!==0')
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
                        .row
                            //.col-12
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

                        .row
                            .col
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

    export default {
        name: "ProductBase",
        data() {
            return {
                provider: null,
                id: 0,
                product: {
                    reference: '',
                    name: '',
                    alertQte: 0,
                    description: '',
                    remark: '',
                    unit_id: '',
                    provider_id: '',
                    subcategory_id: '',
                },
                newColors: {},
                colorsId: []
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
            getUnity(val) {
                this.product.unit_id = val
            },
            getSubCategory(val) {
                this.product.subcategory_id = val
            },
            insert() {
                const colors = _.map(this.colorsId, 'id');
                const product = this.product;

                axios.post('/api/products/new', {colors, product})
                    .then(({data}) => {
                        console.log(data)
                    })
                    .catch(err => console.log(err.response))
            }
        },
        components: {SelectCategory, SelectUnity, Color, SelectedColors, DynamicForm, SearchProvider},
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
