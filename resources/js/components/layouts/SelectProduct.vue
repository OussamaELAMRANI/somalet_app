<template lang="pug">
   .form-group
      label Selectionnez produit
      cool-select(:items='products' :item-text="'name'"  @search='onSearch'
         name='product' placeholder="Designation ..." v-model='product')
         template(slot='item' slot-scope='{ item:p }')
            p
               //strong ({{p.id + ') '}}
               | {{ p.name }}
         template(slot='no-data') {{ noData ? "Aucun produit trouvé" : "Chercher par désignation" }}


</template>

<script>
   import {CoolSelect} from 'vue-cool-select'

   export default {
      name: "SelectProduct",
      components: {CoolSelect},
      data() {
         return {
            products: [],
            noData: false,
            loading: false,
            product: 0,
         }
      },
      methods: {
         onSearch(search) {
            const lettersLimit = 2;
            this.noData = false;

            if (search.length < lettersLimit) {
               this.products = [];
               this.loading = false;
               return;
            }
            this.loading = true;
            let Isearch = search.split('/').join('.');
            Isearch = Isearch.split(`\\`).join('.');

            axios.get(`/api/inventories/production/detail/${Isearch}`,
               {
                  params:{with:'sizes'}
               }
               )
               .then(({data}) => {
                  this.products = data;
                  if (data.length === 0)
                     this.noData = true;

                  this.loading = false;
               })
         },
      },
      watch: {
         product: function (c) {
            this.$emit('sendProduct', c);
         }
      }
   }
</script>

<style scoped>

</style>
