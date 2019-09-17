<template>
   <div id="inventories">
      <big-title title="Stock des produits" position="text-left" classes="text-primary"/>
      <table class="table table-hover table-striped text-center">
         <thead class="thead-dark text-center table-bordered">
         <tr>
            <th>Reference</th>
            <th>Designation</th>
            <th>Qte par</th>
            <th>Qte Total</th>
            <th>Unité</th>
            <th>CR HT</th>
            <th>Prix de Vente</th>
         </tr>
         </thead>
         <tbody class="segment">
            <tr v-for="(inv , k) in inventories">
               <td>{{inv.reference}}</td>
               <td>{{inv.name}}</td>
               <td>{{inv.QTE}}</td>
               <td>{{inv.qte_total}}</td>
               <td><strong>{{inv.unity}}</strong></td>
               <td>{{inv.ht}} <span class="badge">DH</span></td>
               <td>{{inv.prix}} <span class="badge">DH</span></td>
            </tr>
         </tbody>
      </table>
   </div>
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "Inventories",
      components: {BigTitle},
      data() {
         return {
            inventories: []
         }
      },
      mounted() {
         axios.get('/api/receptions/inventories')
            .then(({data}) => {
               this.inventories = data
            })
      }
   }
</script>

<style scoped>

</style>
