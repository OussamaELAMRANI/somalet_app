<template lang="pug">
   #colors_list.container
      table.table.table-striped.text-center.table-bordered
         thead
            tr
               th #
               th Nom
               th Couleur
               th Actions
         tbody
            tr(v-for="(c,i) in colors")
               td {{i+1}}
               td {{c.name}}
               td
                  div.shadow.rounded.rec(:style="'background-color:'+c.color+';'")
               td
                  button.btn.btn-danger Supprimer
                  router-link.btn.btn-info(:to="{name:'updateColor',params:{id:c.id}}") modifier
</template>

<script>
   export default {
      name: "ColorsList",
      data() {
         return {
            colors: []
         }
      },
      created() {
         axios.get('/api/colors').then(({data}) => this.colors = data)
      },
   }
</script>

<style scoped>
   .rec {
      height: 30px;
      width: 100%;
   }
</style>
