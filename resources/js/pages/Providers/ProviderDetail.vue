<template lang="pug">
   #provider_detail.container
      h3 Detail Fournisseur
         span.text-primary {{': '+detail.steName}}
      hr
      .row(v-if="detail != null")
      table-layout(:head-table="infosHeader", :data="detail" )
         tr
            td {{1}}
            td {{detail.steName}}
            td {{detail.numTva}}
            td {{detail.firstName}} {{detail.lastName}}

      table-layout(:head-table="porductsHeader", theme="bg-success text-white" empty-text="Sans Produits" :data="detail.products")
         tr(v-for=" (p,i) in detail.products")
            td {{i+1}}
            td
               img(:src="'/storage/'+p.img" height="90")
            td {{p.reference}}
            td {{p.name}}
            td {{(p.color.name) === null ? 'SANS': p.color.name}}
            td {{p.unit.name}}

</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "ProviderDetail",
      components: {TableLayout},
      data() {
         return {
            detail: null,
            infosHeader: ['#', 'Sociéte', 'TVA', 'Nom Prénom'],
            porductsHeader: ['#', 'image', 'Reference', 'Designation', 'Couleurs', 'Unité']
         }
      },
      mounted() {
         const id = this.$route.params.id
         console.log(id)

         axios.get(`/api/providers/${id}`)
            .then(({data}) => {
               this.detail = data
               console.log(data)
            })
            .catch(err => {
               console.log(err.response)
               this.$router.push('/404')
            })
      }
   }
</script>

<style scoped>

</style>
