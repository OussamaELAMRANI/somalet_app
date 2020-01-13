<template lang="pug">
   #valid_history
      .row
         .col-9
            hr
         .col-3.text-center
            h4.text-uppercase Historique d'entrée aux stock
         .col-10
            table.table.text-center
               thead.bg-success.text-white
                  tr
                     th Numéro du Contenaire
                     th Date
                     th Date de Validation
                     th État
                     th Action
               tbody.segment
                  tr(v-for="(a,i) in arrivals")
                     td {{a.n_dossier}}
                     td {{a.date_facture | humane_date}}
                     td {{a.updated_at | humane_date}}
                     td(:class="(a.state) === 'RECEPTION'? 'text-success' : (a.state) === 'VUE'? 'text-primary' :'text-secondary' ") {{a.state}}
                     td
                        router-link.btn.btn-sm.shadow(:to="{name:'validStock',params:{id:a.id}}" :class="(a.state) === 'RECEPTION'? 'btn-success':  (a.state) === 'VUE'? 'btn-primary' : 'btn-outline-secondary' " ) Verifier

</template>

<script>
   export default {
      name: "ValidContainersHistory",
      data() {
         return {
            arrivals: []
         }
      },
      mounted() {
         this.getValidContainers()
      },
      methods: {
         getValidContainers() {
            axios.get('/api/inventories/valid-history')
               .then(({data}) => {
                  this.arrivals = data
               })
               .catch(err => console.log(err.response))
         }
      }
   }
</script>

<style scoped>

</style>
