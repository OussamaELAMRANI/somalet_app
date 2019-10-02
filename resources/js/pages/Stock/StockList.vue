<template lang="pug">
   #list_stock
      big-title(title="STOCK RECEPTIONNÉ" position="text-left" classes="text-primary")
      .row
         .col-10
            //stock-table(:headTable="tabHeader" )
            table.table.text-center
               thead.bg-success.text-white
                  tr
                     th Numéro du Contenaire
                     th Date
                     th Fournisseur
                     th État
                     th Action
               tbody.segment
                  tr(v-for="(a,i) in arrivals")
                     td {{a.n_dossier}}
                     td {{a.date_facture | humane_date}}
                     td {{a.provider.steName}}
                     td(:class="(a.state) === 'RECEPTION'? 'text-success' : (a.state) === 'VUE'? 'text-primary' :'text-secondary' ") {{a.state}}
                     td
                        router-link.btn.btn-sm.shadow(:to="{name:'validStock',params:{id:a.id}}" :class="(a.state) === 'RECEPTION'? 'btn-success':  (a.state) === 'VUE'? 'btn-primary' : 'btn-outline-secondary' " ) Verifier
</template>

<script>
    import StockTable from "@/components/Stock/StockTable";
    import BigTitle from "@/components/layouts/BigTitle";

    export default {
        name: "StockList",
        components: {BigTitle, StockTable},
        data() {
            return {
                tabHeader: ['', 'Facture', 'Dossier', 'Date'],
                arrivals: []
            }
        },
        mounted() {
            axios.get('/api/inventories/no-valid')
                .then(({data}) => {
                    this.arrivals = data
                })
        }
    }
</script>

<style scoped>
   .segment {
      margin: 0;
      border-bottom: 2px solid green;
   }
</style>
