<!-- Does mean which container that Products Entree -->

<template lang="pug">
   #product_detail
      table-layout(:head-table="head_table", :data="detail")
         tr
            td {{detail.id}}
            td
               img(:src="'/storage/'+detail.img" height="80px")
            td {{detail.reference}}
            td {{detail.name}}
            td {{detail.alertQte}}
            td {{detail.type}}
            td {{detail.unit.name}}
            td {{detail.subcategory.sub_category}}
            td {{detail.color.name}}
            td {{detail.provider.steName}}
      h3.text-uppercase.text-secondary conteneurs
      hr
      .container
         table(class="table table-hover table-striped")
            thead(class="bg-success text-white")
               tr
                  th #
                  th Numéro
                  th Type
                  th Date
                  th Quantité
                  th Rapport
            tbody
               tr(v-for="(c, i) in detail.arrivals ")
                  td {{i+1}}
                  td {{c.n_dossier}}
                  td {{c.type}}
                  td {{c.date_facture | humane_date}}
                  td {{c.pivot.qte_facture}}
                     small.text-secondary  {{" ("+detail.type}})
                  td {{c.pivot.rapport_qte}}


</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "DetailProduct",
      components: {TableLayout},
      data() {
         return {
            detail: {
               img: '',
               reference: '',
               name: '',
               alertQte: '',
               type: '',
               unit: {},
               subcategory: {},
               color: {},
               provider: {},
            },
            head_table: ['#', 'Image', 'Reference', 'Designation', 'Stock min', "Type", "Unité", "Catégorie", "Couleur", "Fournisseur"],
         }
      },
      mounted() {
         axios.get('/api/products/1?with=unit,provider,color,category,arrivals')
            .then(({data}) => {
               this.detail = {...data}
            })
            .catch(err => console.log(err.response))
      }
   }
</script>

<style scoped>

</style>
