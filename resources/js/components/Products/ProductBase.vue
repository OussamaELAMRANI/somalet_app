<template lang="pug">
   #product_base.mb-5
      transition( name="bounce")
         search-provider(@provider="getProvider" v-if='provider == null')
         div(v-else)
            .row.align-items-start
               .col-12
                  button(class="btn btn-secondary btn-sm align-self" @click="provider=null")
                     i(class="fas fa-arrow-left")
                     | Autre Fournisseur
            .dropdown-divider
            .row.mb-2
               .col-3
                  h5.text-success.mt-4 Nouveau produit du Fournissuer :
                  p.text-primary {{provider.steName}}
               .col-9
                  picture-input#art-portrait(width='150' height='150' :margin='16' accept='image/*' size='10' ref='portraits'
                     button-class='btn btn-sm btn-info' :prefill='img_url'
                     :custom-strings="{upload: 'uploaded', drag: 'Image de produit ...'}")
            .dropdown-divider
            form(@submit.prevent='')
               .form-group
                  .row
                     .col-4
                        p.text-uppercase.text-black.mb-0 Produit
                        small.text-secondary Information identique de la produit
                     .col-4
                        label(for='reference') Réference du produit
                        input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference' name='reference' v-validate="'required'")
                        small.text-danger(v-show="errors.has('reference')") Vous devez saisir la réference !
                     .col-4
                        label(for='name') Nom du Produit
                        input#name.form-control(type='text' placeholder='SIRET ...' v-model='product.name')
                  .dropdown-divider
                  select-unity(@sendUnity="getUnity")
                  .dropdown-divider
                  .row
                     .col-4
                        p.text-uppercase.text-black.mb-0 Alert et Catégorie
                        ul
                           li
                              small.text-secondary Quantité d'alert c'est l'indice qui permet de connaître l'état de stock avant de se détruire
                           li
                              small.text-secondary Catégorie de ce produit qui se base à leur type [MP, PF, MC]
                     .col-4
                        label(for='stockAlerte') Alert quantit&eacute; minimum
                        input#stockAlerte.form-control(type='Number' placeholder='Alert de Stock ...' name='alert' v-model='product.alertQte'
                           v-validate="'required'")
                        small.text-danger(v-show="errors.has('alert')") Vous devez saisir la Quantité d'alert !
                     select-category(@sendSubCategory="getSubCategory")
                  .dropdown-divider
                  .row
                     .col-4
                        p.text-uppercase.text-black.mb-0 Type de Produit
                        ul
                           li
                              small.text-secondary Matiere première pour fabriquer un produit fini
                           li
                              small.text-secondary Produit fini: est prêt pour les commandes
                           li
                              small.text-secondary Marchandise est distiné au vendre (Négociable)
                           li
                              small.text-secondary Consommable comme (matiere première) avec un seul utilisation
                           li
                              small.text-secondary Emballage comme les cartons ...

                     .col-8
                        product-type(:types='types' @getType="getType")
                  .dropdown-divider
                  .row.align-items-center
                     .col-12.mb-4
                        button.btn-sm.btn-outline-success( data-toggle="modal" data-target="#colorModal")
                           i.fa.mr-1.fa-plus
                           | Ajouter une couleur
                  transition(name="fade")
                     #colorModal.modal.fade(tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel')
                        .modal-dialog.modal-lg(role='')
                           .modal-content
                              .modal-header
                                 h5#exampleModalLabel.modal-title Couleur palette
                                 button.close(type='button' data-dismiss='modal' aria-label='Close')
                                    span(aria-hidden='true') &times;
                              .modal-body.row
                                 .col
                                    selected-colors(:new-color='newColors' @giveColors='getAllColors')
                                 .col
                                    color(@getColors='getColors')
                  .row
                     .col
                        label(for='desc') Description
                        textarea#desc.form-control(placeholder='Description du produit  ...' v-model='product.description')
                     .col
                        label(for='remark') Remarque
                        textarea#remark.form-control(placeholder='Remarque ...' v-model='product.remark')
                     .dropdown-divider
                  .row.float-right
                     .col.my-4
                        button.btn.btn-outline-success(@click='goBack()') Annuler
                        button.btn.btn-success.ml-2(@click='insert') Enregister

</template>

<script>
   import SearchProvider from "@/components/Provider/SearchProvider";
   import DynamicForm from "@/components/layouts/DynamicForm";
   import SelectedColors from "@/components/Colors/SelectedColors";
   import Color from "@/components/Colors/Colors";
   import SelectUnity from "@/components/layouts/SelectUnity";
   import SelectCategory from "@/components/layouts/SelectCategory";
   import PictureInput from "vue-picture-input";
   import BigTitle from "@/components/layouts/BigTitle";
   import ProductType from "@/components/Products/ProductType";

   export default {
         name: "ProductBase",
      data() {
         return {
            provider: null,
            id: 0,
            type: 'NEG',
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
            types: [
               {id: 'MP', title: "Matière première", isActive: false, img: 'boxes/flask.svg'},
               {id: 'PF', title: "Produit finis", isActive: false, img: 'boxes/shose.svg'},
               {id: 'NEG', title: "Marchandise", isActive: true, img: 'boxes/handshake.svg'},
               {id: 'CONS', title: "Consommable", isActive: false, img: 'boxes/recycle.svg'},
               {id: 'EMB', title: "Emballage", isActive: false, img: 'boxes/box.svg'}
            ],
            hasColor: false
         }
      },
      methods: {
         goBack() {
            this.$router.push({name: 'listProducts'})
         },
         getProvider(provider) {
            this.provider = provider
            this.product.provider_id = provider.id
         },
         getColors(data, hasColor) {
            if (!hasColor)
               this.newColors = data.colors
            else
               this.$notification.error("Cette couleur déjà existe")
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
         getType(t) {
            // let val = ev.target.options[ev.target.options.selectedIndex].value
            this.type = t
         },
         insert() {
            return this.$validator.validateAll().then((isValid) => {
               if (isValid) {
                  const formData = new FormData()
                  const images = this.$refs.portraits
                  formData.append('img', images.file)
                  formData.append('colors', JSON.stringify(this.colorsId))
                  formData.append('product', JSON.stringify(this.product))

                  axios.post('/api/products/new', formData, {
                     headers: {
                        'Content-Type': 'multipart/form-data'
                     }
                  }).then(({data}) => {
                     console.log(data)
                     this.$router.push({name: 'listProducts'})
                  }).catch(err => console.log(err.response))

               } else {
                  this.$notification.error("Réference est obligatoire !")
                  this.$scrollTo('#reference', 700)
               }

            });
         }

      },
      components: {
         ProductType,
         BigTitle,
         PictureInput, SelectCategory, SelectUnity, Color, SelectedColors, DynamicForm, SearchProvider
      },
   }
</script>

<style>
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

   .fade-enter-active, .fade-leave-active {
      transition: opacity .5s ease;
   }

   .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
   {
      opacity: 0;
   }

   canvas.picture-preview {
      z-index: 2 !important;
   }
</style>
