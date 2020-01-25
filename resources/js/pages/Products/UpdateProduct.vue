<template lang="pug">
   #update_product.container
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
               .modal-footer
                  button.btn.btn-outline-dark(data-dismiss="modal") Fermer


      .row.justify-content-center
         .col-9
            picture-input#art-portrait(width='150' height='150' :margin='16' accept='image/*' size='10' ref='portraits'
               button-class='btn btn-sm btn-info' :prefill="'/storage/'+product.img"
               :custom-strings="{upload: 'uploaded', drag: 'Image de produit ...'}")

      form(@submit.prevent='')
         .form-group
            .row
               .col-4
                  p.text-uppercase.text-black.mb-0 Produit
                  small.text-secondary Information identique de la produit
               .col-4
                  label(for='reference') Réference du produit
                  input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference' name='reference')
               .col-4
                  label(for='name') Nom du Produit
                  input#name.form-control(type='text' placeholder='SIRET ...' v-model='product.name' )
            .dropdown-divider
            select-unity(@sendUnity="getUnity" :selectedUnity="unit.name")
            .dropdown-divider
            .row
               .col-4
                  p.text-uppercase.text-black.mb-0 Alert et Catégorie
                  ul
                     li
                        small.text-secondary Quantité d'alert c'est l'indice qui permet de connaître l'état de stock avant de se détruire
               .col-4
                  label(for='stockAlerte') Alert quantit&eacute; minimum
                  input#stockAlerte.form-control(type='Number' placeholder='Alert de Stock ...' name='alert' v-model='product.alertQte')
               select-category(@sendSubCategory="getSubCategory", :selectedCategory="category.sub_category")

            .dropdown-divider
            .row.align-items-center
               .col.mb-4
                  button.btn-sm.btn-outline-success(data-toggle="modal" data-target="#colorModal")
                     i.fa.mr-1.fa-plus
                     | Modifier la couleur
               .col-6(v-if="product.type === 'PF'")
                  .row.segment.text-center
                     .col-3(v-for="(s, i) in sizes").my-1
                        .card(:style="(sizes[i].weight === 0 || sizes[i].weight === '')? '': 'background-color: orange'")
                           .card-header {{s.size}}
                           input.form-control(type="number" v-model.number="sizes[i].weight" min="0")
            .row
               .col
                  label(for='desc') Description
                  textarea#desc.form-control(placeholder='Description du produit...' v-model='product.description')
               .col
                  label(for='remark') Remarque
                  textarea#remark.form-control(placeholder='Remarque ...' v-model='product.remark')
               .dropdown-divider
            .row.float-right
               .col.my-4
                  button.btn.btn-outline-success(@click='$router.back()') Annuler
                  button.btn.btn-success.ml-2(@click='updateNow') Modifier
</template>

<script>
   import SelectUnity from "@/components/layouts/SelectUnity";
   import SelectCategory from "@/components/layouts/SelectCategory";
   import PictureInput from "vue-picture-input";
   import {Chrome} from 'vue-color'
   import ColorPicker from '@radial-color-picker/vue-color-picker';
   import SelectedColors from "@/components/Colors/SelectedColors";
   import Color from "@/components/Colors/Colors";

   export default {
      name: "UpdateProduct",
      data() {
         return {
            newColors: {},
            product: {
               img: '',
               name: '',
            },
            img_url: '',
            color: '#ffffff',
            sizes: [],
            colorsId: [],
            shoes: null,
            unit: {
               name: ''
            },
            category: {
               sub_category: ''
            },
            image: null
         }
      },
      mounted() {
         const {id} = this.$route.params;
         this.bootSizes();
         axios.get(`/api/products/${id}`, {
            params: {
               with: 'color,sizes,unit,category'
            }
         }).then(({data}) => {
            const {sizes, unit, subcategory} = data
            this.shoes = sizes;
            this.unit = unit;
            this.category = subcategory;
            this.product = data
         })
            .catch((err) => this.$router.push('/404'))
      },
      methods: {
         bootSizes: function () {
            axios.get('/api/products/sizes')
               .then(({data}) => {
                  _.forEach(data, (s) => {
                     this.sizes.push({...s, weight: ""})
                  })

               })
               .catch(error => console.log(error))
         },
         getAllColors(data) {
            this.colorsId = _.cloneDeep(data)
            if (data.length > 0) {
               const {id, name} = this.colorsId[0]
               const color = this.product.name.substr(this.product.name.lastIndexOf('/'), this.product.name.length);
               this.product.color_id = id
               this.product.name = this.product.name.replace(color, `/${name}`)
            }

         },
         getUnity(u) {
            this.product.unit_id = u
         },
         getSubCategory(c) {
            this.product.subcategory_id = c
         },
         goBack() {
            this.$router.back()
         },
         updateNow() {
            const formData = new FormData();
            const {id} = this.$route.params;
            const images = this.$refs.portraits
            const {name} = images.file;

            formData.append('product', JSON.stringify(this.product));
            if (!this.product.img.includes(name)) {
               formData.append('img', images.file);
            }


            axios.post(`/api/products/${id}/update`, formData
            ).then(({data}) => {
               console.log(data)
               this.$router.back()
            }).catch(err => console.log(err))
         },
         getColors(data, hasColor) {
            if (!hasColor)
               this.newColors = data.colors
            else
               this.$notification.error("Cette couleur déjà existe")
         },
         imageChanged(file) {
            this.image = this.$refs.portraits
         }
      },
      watch: {
         product: function (p) {
            const {color} = p;
            this.color = color.color
         },
         shoes: function (s) {
            const {type} = this.product;

            if (type === 'PF') {
               const oldSizes = this.sizes;
               for (let i = 0; i < oldSizes.length; i++) {
                  const hasId = _.filter(s, (dot) => dot.id === oldSizes[i].id)
                  if (hasId.length > 0) {
                     const {weight} = hasId[0].pivot;
                     this.sizes[i].weight = weight;
                  }
               }
            }
         }
      },

      components: {
         SelectUnity, PictureInput, SelectedColors, Color,
         SelectCategory, Chrome, ColorPicker
      }
   }
</script>

<style>
   canvas.picture-preview {
      z-index: 2 !important;
   }

</style>
