<template lang="pug">
   #productionList
      h4 Liste des Commandes aux Productions
      hr
      .row
         .col-10
            table.table.table-striped.table-bordrer.text-center
               thead.bg-dark.text-white
                  tr
                     th Numéro
                     th Date de commande
                     th État
                     th Afficher
               tbody
                  tr(v-for="(p) in productions")
                     td {{p.id}}
                     td {{p.date_cmd }}
                     td
                        p.badge(:class="(p.state==='ATTENTE') ? 'badge-warning' : 'badge-success'") {{p.state}}
                     td
                        button.btn.btn-outline-danger.rounded-pill.mx-2 Supprimer
                        router-link(:to="{name:'detailProductionOrder', params:{id:p.id}}"
                           class="btn  rounded-pill btn-outline-primary") Détails
</template>

<script>
   export default {
      name: "ProductionOrderList",
      data() {
         return {
            productions: []
         }
      },
      mounted() {
         this.getProductionList();
      },
      methods: {
         getProductionList() {
            axios.get(`/api/receptions/production-list`)
               .then(({data}) => {
                  this.productions = data;
               }).catch(err => console.log(err))
         }
      }
   }
</script>

<style scoped>

</style>
