<template lang="pug">
   #container_detail
      .row.align-items-center.justify-content-center
         .col-9
            hr
         .col-3
            big-title(title="Detail de contenaire", :separator="false" :classes="['text-uppercase']")
         .col-10
            .row.segment.text-center
               .col-4
                  h4 Contenaire N:
                     strong {{detail.n_dossier}}
               .col-4
                  h4 Date:
                     strong {{detail.date_facture | humane_date}}
               .col-4
                  h4.text-primary
                     strong {{detail.state}}

         .col-10
            table.table.table-bordered.table-striped.text-center
               thead.bg-primary.text-light
                  tr
                     th #
                     th Produit
                     th Qte
                     th Rapport
                     th Qte Entrée
                     th Rapport Entrée
               tbody
                  tr(v-for="(p,i) in products")
                     td {{i+1}}
                     td {{p.name}}
                     td {{p.pivot | getByChoice('qte_facture')}}
                     td {{p.pivot | getByChoice('rapport_qte')}}
                     td {{p.pivot | getByChoice('qte_reception')}}
                     td {{p.pivot | getByChoice('qte_rapport_reception')}}
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "DetailContainer",
      components: {BigTitle},
      data() {
         return {
            detail: {
               n_dossier:'',
               date_facture:'',
               state:'',
            },
            products: null
         }
      },
      mounted() {
         const {id} = this.$route.params
         axios.get(`/api/containers/${id}/detail`)
            .then(({data}) => {
               const {product} = data
               this.detail = data
               this.products = product
            })
            .catch(err => {
               console.log(err)
               this.$router.push('/404')
            })
      }
   }
</script>

<style scoped>

</style>
