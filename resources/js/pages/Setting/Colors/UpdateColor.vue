<template lang="pug">
   #update_color
      .container
         .row.justify-content-center
            .col-4
               label Nom de couleur
               input.form-control(v-model="color.name")
            .col-4
               Chrome(v-model="colorName")
            .col-12.text-right
               button.btn.btn-success(@click="updateColor") Modifer

</template>

<script>
   import {Chrome} from 'vue-color'
   import ColorPicker from '@radial-color-picker/vue-color-picker';
   import StatusIndicator from 'vue-status-indicator'

   export default {
      name: "UpdateColor",
      components: {
         Chrome,
         ColorPicker
      },
      data() {
         return {
            color: {
               name: '',
               color: '',
            },
            colorName: {
               hex: ''
            }
         }
      },
      mounted() {
         const {id} = this.$route.params
         axios.get(`/api/colors/${id}`)
            .then(({data}) => {
               this.color = data
               this.colorName.hex = data.color
            })
            .catch(err => console.log(err))
      },
      methods: {
         updateColor() {
            const {id} = this.$route.params
            axios.put(`/api/colors/${id}`, {
               color: this.color
            })
               .then(({data}) => {
                  this.$router.push({name:'colorsList'})
               })
               .catch(err => console.log(err))
         }
      },
      watch: {
         'colorName.hex': function (c) {
            this.color.color = c
         }
      }
   }
</script>

<style scoped>

</style>
