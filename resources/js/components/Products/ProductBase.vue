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
                                input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.ref' :disabled='id!==0')
                            .col
                                label(for='name') Nom du Produit
                                input#name.form-control(type='text' placeholder='SIRET ...' v-model='product.name')
                            .dropdown-divider

                        .row.align-items-center
                            .col
                                selected-colors(:new-color='newColors' @giveColors='getAllColors')
                            .col
                                color(@getcolors='getColors')

                        //select-unity()

                        .row
                            .col
                                label(for='stockAlerte') Alert quantit&eacute; minimum
                                input#stockAlerte.form-control(type='Number' placeholder='stockAlerte ...' v-model='product.alertQte')
                            .dropdown-divider
                        //.row
                            .col-12
                                label(for='type') Selectionnez le Type du produit
                                select#type.form-control(v-on:change='getType')
                                    option(selected='selected' disabled='disabled') Selectionnez...
                                    option(v-for=" t in ['MATIERE_PREMIERE','PRODUIT_FINI']" :selected='(t === type)')
                                        | {{t}}
                            .col-12
                                label(for='category') Selectionnez la categorie
                                select#category.form-control(v-on:change='getCategory')
                                    option(selected='selected' disabled='disabled') Selectionnez...
                                    template(v-for='t in categories')
                                        optgroup(:label='t.category_name')
                                            option(v-for='sub in t.sub_categories' :id="'subcategory_'+sub.id" :selected='(sub.id == subcategory_id)')
                                                | {{sub.sub_category}}
                        .row
                            .col
                                label(for='desc') Description
                                textarea#desc.form-control(placeholder='Description du produit  ...' v-model='product.desc')
                            .col
                                label(for='note') Remarque
                                textarea#note.form-control(placeholder='Remarque ...' v-model='product.note')
                            .dropdown-divider

                        .row
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

    export default {
        name: "ProductBase",
        data() {
            return {
                provider: null,
                id: 0,
                product: {
                    ref: '',
                    name: '',
                    alertQte: 0,
                    desc: '',
                    note: '',
                },
                newColors: {},
                colorsId: []
            }
        },
        methods: {
            getProvider(provider) {
                this.provider = provider
            },
            getColors(data, hasColor) {
                if (!hasColor)
                    this.newColors = data.colors
            },

            getAllColors(data) {
                this.colorsId = data
            },
            insert() {
                const colors = _.map(this.colorsId, 'id');

                axios.post('/api/products/new', {colors})
                    .then(({data}) => {
                        console.log(data)
                    })
            }
        },
        components: {SelectUnity, Color, SelectedColors, DynamicForm, SearchProvider},
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
