<template lang="pug">
   .form-group
      label Choisissez une couleur
      cool-select(:items='colors' :item-text="'name'"  @search='onSearch'
         name='color' placeholder="la couleur..." v-model='color')
         template(slot='item' slot-scope='{ item:c }')
            div.d-flex
               div.square.shadow.mx-5(:style="'background-color:'+ c.color")
               p.lead {{c.name}}

         template(slot='no-data') {{ noData ? "Aucune couleur trouvé" : "Chercher par couleur" }}
</template>

<script>
   import {CoolSelect} from 'vue-cool-select'

   export default {
      name: "SelectColor",
      components: {CoolSelect},
      props: {
         color_id: {
            default: null
         }
      },
      data() {
         return {
            colors: [],
            color: {},
            noData: false,
            loading: false,
         }
      },
      methods: {
         onSearch(search) {
            const lettersLimit = 2;
            this.noData = false;

            if (search.length < lettersLimit) {
               this.colors = [];
               this.loading = false;
               return;
            }
            this.loading = true;
            let Isearch = search.split('/').join('.');
            Isearch = Isearch.split(`\\`).join('.');

            axios.get(`/api/colors/search/${Isearch}`)
               .then(({data}) => {
                  this.colors = data;
                  if (data.length === 0)
                     this.noData = true;
                  this.loading = false;
               })
         },
      },
      watch: {
         color: function (c) {
            this.$emit('emitColor', c);
         }
      }
   }
</script>

<style scoped>
   .square {
      width: 30px;
      height: 30px;
      border-radius: 50%;
   }
</style>
