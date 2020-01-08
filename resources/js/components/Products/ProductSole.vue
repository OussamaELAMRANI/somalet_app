<template lang="pug">
   #sole.row
      .col-6
         form(@submit.prevent='')
            .form-group
               .row.justify-content-center.my-3
                  .col-4
                     picture-input#art-portrait(width='150' height='150' :margin='16' accept='image/*' size='10' ref='portraits'
                        button-class='btn btn-sm btn-info'
                        :custom-strings="{upload: 'uploaded', drag: 'Image de produit ...'}")
               .row
                  .col-6
                     label(for='reference') Réference du produit
                     input#reference.form-control(type='text' placeholder='Ref ...' v-model='product.reference' name='reference' )
                     //small.text-danger(v-show="errors.has('reference')") Vous devez saisir la réference !
                  .col-6
                     label(for='name') Nom du Produit
                     input#name.form-control(type='text' placeholder='Designation ...' v-model='product.name' )
                  .col-6
                     label(for='stockAlerte') Alert quantit&eacute; minimum
                     input#stockAlerte.form-control(type='Number' placeholder='Alert de Stock ...' name='alert' v-model='product.alertQte')
                  select-category(@sendSubCategory="getSubCategory")
                  .col-12
                     label(for='desc') Description
                     textarea#desc.form-control(placeholder='Description du produit  ...' v-model='product.description')
                  .col-12.my-3
                     button.btn.btn-sm.btn-outline-success( data-toggle="modal" data-target="#colorModal")
                        i.fa.mr-1.fa-plus
                        | Ajouter une couleur
                     .row.justify-content-start
                        .square.shadow.rounded.my-3.mx-1(v-for="c in computeColors" :style="'background-color:'+c.color")
                  .col-12.text-right
                     hr
                     button.btn.btn.btn-primary.text-white(@click="insert") Ajouter L'article
      .col-6
         .row.segment.text-center
            .col-3(v-for="(s, i) in sizes").my-1
               .card(:style="(sizes[i].weight === 0 || sizes[i].weight === '')? '': 'background-color: orange'")
                  .card-header {{s.size}}
                  input.form-control(type="number" v-model.number="sizes[i].weight" min="0")

</template>

<script>
   import PictureInput from "vue-picture-input";
   import SelectCategory from "@/components/layouts/SelectCategory";

   export default {
      components: {
         SelectCategory,
         PictureInput
      },
      props: {
         colors: {},
         provider: {},
      },
      name: "ProductSole",
      data() {
         return {
            product: {
               reference: '',
               name: '',
               alertQte: 0,
               description: '',
               remark: '',
               unit_id: 1,
               provider_id: _.cloneDeep(this.provider),
               subcategory_id: '',
               rapport: 0,
            },
            sizes: [],
         }
      },
      mounted() {
         this.bootSizes();
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
         getSubCategory(val) {
            this.product.subcategory_id = val
         },
         insert() {
            const formData = new FormData()
            const images = this.$refs.portraits
            formData.append('img', images.file)
            formData.append('colors', JSON.stringify(this.colors))
            formData.append('product', JSON.stringify(this.product))
            formData.append('sizes', JSON.stringify(this.sizes))

            axios.post('/api/products/new', formData, {
               headers: {
                  'Content-Type': 'multipart/form-data'
               }
            }).then(({data}) => {
               console.log(data)
               this.$router.push({name: 'listProducts'})
            }).catch(err => console.log(err.response))


         },
      },
      computed: {
         computeColors() {
            return this.colors;
         }
      },

   }
</script>

<style scoped>
   .square {
      height: 25px;
      width: 25px;
   }
</style>
