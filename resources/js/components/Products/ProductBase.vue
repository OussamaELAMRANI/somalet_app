<template lang="pug">
   #product_base.mb-5
      #colorModal.modal.fade(tabindex='-1' role='modal')
         .modal-xl.modal-dialog
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

      transition( name="bounce")
         search-provider(@provider="getProvider" v-if='provider == null')
         div(v-else)
            .row.align-items-start
               .col-12
                  button(class="btn btn-secondary btn-sm align-self" @click="provider=null")
                     i(class="fas fa-arrow-left")
                     | Autre Fournisseur
            hr
            .row.justify-content-center
               .col-4
                  p.text-uppercase.text-black.mb-0 Type de Produit
                  ul
                     li
                        small.text-secondary Matiere première pour fabriquer un produit fini
                     li
                        small.text-secondary {{'Produit fini' }}: est prêt pour les commandes (Semelle)
                     li
                        small.text-secondary Marchandise est distiné au vendre (Négociable)
                     li
                        small.text-secondary Consommable comme (matiere première) avec un seul utilisation
                     li
                        small.text-secondary Emballage comme les cartons ...
               .col-8
                  product-type(:types='types' @getType="getType")
            hr
            template(v-if="type === 'PF'")
               ProductSole(:colors="colorsId", :provider="product.provider_id")

            template(v-else)
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
                           input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference' name='reference')
                           //small.text-danger(v-show="errors.has('reference')") Vous devez saisir la réference !
                        .col-4
                           label(for='name') Nom du Produit
                           input#name.form-control(type='text' placeholder='SIRET ...' v-model='product.name' )
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
                           )
                           //small.text-danger(v-show="errors.has('alert')") Vous devez saisir la Quantité d'alert !
                        select-category(@sendSubCategory="getSubCategory")

                     .dropdown-divider
                     .row.align-items-center
                        .col-12.mb-4
                           button.btn-sm.btn-outline-success( data-toggle="modal" data-target="#colorModal")
                              i.fa.mr-1.fa-plus
                              | Ajouter une couleur
                     .row.justify-content-start
                        .square.shadow.rounded.my-3.mx-1(v-for="c in computeColors" :style="'background-color:'+c.color")

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
   import ProductSole from "@/components/Products/ProductSole";

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
               type: 'NEG'
            },
            newColors: {},
            colorsId: [],
            img_url: '',
            types: [
               {id: 'MP', title: "Matière première", isActive: false, img: 'boxes/flask.svg'},
               {id: 'PF', title: "Production", isActive: false, img: 'boxes/shose.svg'},
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
            this.colorsId = _.cloneDeep(data)
         },
         getUnity(id, val) {
            this.product.unit_id = id;
            this.product.rapport = (val.indexOf('/') === -1) ? 0 : 1;
         },
         getSubCategory(val) {
            this.product.subcategory_id = val
         },
         getType(t) {
            this.type = t
            this.product.type = t
         },
         insert() {
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
            })
               .catch(err => console.log(err.response))

            // } else {
            //    this.$notification.error("Réference est obligatoire !")
            //    this.$scrollTo('#reference', 700)
         }

      },
      mounted() {
         //pour determiner si l'etat de la vue en mode : Ajout ou modification
         //si id == null => Ajout
         //si id != null => Modification
         const id = this.$route.params.id

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
      computed:{
         computeColors() {
            return this.colorsId;
         }
      },
      components: {
         ProductSole,
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

   .color_circle {
      height: 50px;
      width: 50px;
      border-radius: 50%;
   }
   .square {
      height: 25px;
      width: 25px;
   }
</style>
