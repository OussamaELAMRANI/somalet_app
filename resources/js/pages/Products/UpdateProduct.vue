<template lang="pug">
   #update_product.container
      .row.justify-content-center
         .col-9
            picture-input#art-portrait(width='150' height='150' :margin='16' accept='image/*' size='10' ref='portraits'
               button-class='btn btn-sm btn-info' :prefill="'/storage/'+product.img"
               :custom-strings="{upload: 'uploaded', drag: 'Image de produit ...'}")

      .col-12(v-show="isLoading")
         loading-page
      form(@submit.prevent='' v-show='!isLoading')
         .form-group
            .row
               .col-4
                  p.text-uppercase.text-black.mb-0 Produit
                  small.text-secondary Information identique de la produit
               .col-4
                  label(for='reference') Réference du produit
                  input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference' name='reference' disabled)
               .col-4
                  label(for='name') Nom du Produit
                  .input-group
                     input#name.form-control(type='text' placeholder='Designation ...' v-model='product.name' )
                     .input-group-prepend()
                        span.input-group-text {{slice}}
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
            .row
               .col-4.my-1
                  p.text-uppercase.text-black.mb-0 Couleurs
                  small.text-secondary Choisissez la couleur pour modifer ce produit
               .col-4.my-1
                  SelectColor(@emitColor="getColor")
               .col.mb-4
                  label Couleur ({{color | getByChoice('name')}})
                  .square.shadow(:style="{ background: (color) ? color.color: ''  }")
            .dropdown-divider
            .row(v-show="product.type === 'PF'")
               .col-4.my-1
                  p.text-uppercase.text-black.mb-0 Pointures
                  small.text-secondary Choisissez les pointures à modifer
               .col-8
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
   import {mapActions} from 'vuex'
   import SelectColor from "@/components/layouts/SelectColor";
   import LoadingPage from "@/components/layouts/LoadingPage";

   export default {
      name: "UpdateProduct",
      data() {
         return {
            isLoading: true,
            slice: '',
            newColors: {},
            product: {
               img: '',
               name: '',
            },
            img_url: '',
            color: {"color": "#FFF",},
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
         this.bootSizes();
         this.oldProduct();
      },
      methods: {
         oldProduct() {
            const {id} = this.$route.params;
            const router = this.$router
            this.load({
               url: `/api/products/${id}`,
               params: {
                  params: {
                     with: 'color,sizes,unit,category'
                  }
               },
               router
            })
               .then(data => {
                  if (this.isEmpty(data)) {
                     router.push('/404');
                     return
                  }
                  const {sizes, unit, subcategory, color} = data
                  this.color = {...color};
                  this.shoes = sizes;
                  this.unit = unit;
                  this.category = subcategory;
                  this.product = data
                  this.isLoading = false;
               })
               .catch(err => {
                  console.log(err)
                  router.push({name: 'notFound'});
               })
         },
         getColor(color) {
            if (color) {
               this.color = color
               this.product.color_id = color.id
            }
         },
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
            this.isLoading = true;
            const formData = new FormData();
            const {id} = this.$route.params;
            const images = this.$refs.portraits
            const {name} = images.file;
            this.product.name = this.product.name + this.slice;
            formData.append('product', JSON.stringify(this.product));

            if (this.product.img !== null) {
               if (!this.product.img.includes(name)) {
                  formData.append('img', images.file);
               }
            } else {
               formData.append('img', images.file);
            }

            if (this.product.type === 'PF') {
               const sizes = this.sizes.filter(s => Number(s.weight) !== 0);
               formData.append('sizes', JSON.stringify(sizes));
            }

            axios.post(`/api/products/${id}/update`, formData)
               .then(({data}) => {
                  console.log(data)
                  this.isLoading = false;
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
         },
         ...mapActions({load: 'updateStore/getData'}),
      },
      watch: {
         product: function (p) {
            let {name} = p;
            name = name.split('/');
            name[name.length - 1] = null
            this.slice = this.color.name
            p.name = name.join('/')
         },
         color: function (c) {
            if (c !== null)
               this.slice = c.name
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
         },
         sizes: function (sizes) {
            const {type} = this.product;
            const shoes = this.shoes
            if (type === 'PF') {
               const oldSizes = sizes;
               for (let i = 0; i < oldSizes.length; i++) {
                  const hasId = _.filter(shoes, (dot) => dot.id === oldSizes[i].id);
                  if (hasId.length > 0) {
                     const {weight} = hasId[0].pivot;
                     this.sizes[i].weight = weight;
                  }
               }
            }
         }
      },
      components: {
         LoadingPage,
         SelectColor,
         SelectUnity, PictureInput, SelectedColors, Color,
         SelectCategory, Chrome, ColorPicker
      }
   }
</script>

<style>
   canvas.picture-preview {
      z-index: 2 !important;
   }

   .square {
      width: 30px;
      height: 30px;
      border-radius: 50%;
   }
</style>
