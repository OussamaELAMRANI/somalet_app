<template lang="pug">
   #valid_container
      .row.justify-content-center
         .col-8
            table.table.text-center
               thead.bg-success.text-white
                  tr
                     th Numéro de Contenaire
                     th Date
                     th Fournisseur
               tbody.segment
                  tr
                     td {{(container)== null ? null : container.n_dossier}}
                     td {{(container)== null ? null : container.date_facture}}
                     td {{(container)== null ? null : provider.steName}}
      .row
         .col-12
            table.table.text-center
               thead.bg-primary.text-white
                  tr
                     th Produit
                     th Quantité
                     th Rapport
                     th Total Reception
                     th Qte Entrée
                     th Rapport Entrée
                     th Total Reception
                     th Unité
                     th Prix Unitaire
                     th Prix de Vente
               tbody
                  tr(v-for="(p,i) in container.product" :class="isOkay(p.pivot) ? '': 'bg-danger text-white'")
                     td {{p.name}}
                     td {{p.pivot | getByChoice('qte_facture')}}
                     td {{p.pivot | getByChoice('rapport_qte')}}
                     td(class="text-primary") {{p.pivot.rapport_qte * p.pivot.qte_facture}}
                     td {{p.pivot | getByChoice('qte_reception')}}
                     td {{p.pivot | getByChoice('qte_rapport_reception')}}
                     td(class="text-success") {{p.pivot.qte_reception * p.pivot.qte_rapport_reception}}

                     td {{p.unit.name}}
                     td
                        input.form-control.form-control-sm(type="number" placeholder="Modifier", v-model.number="products[i].pivot.price_unit_ht")
                     td
                        input.form-control.form-control-sm(type="number", placeholder="Prix de Vente" v-model.number="products[i].pivot.sell_price")
               tfoot
                  tr
                     td.text-right(colspan="10")
                        button.btn.btn-outline-success.mx-1(@click="validContainer(true)") VALIDER TOUT

</template>

<script>
   export default {
      name: "ValidContainer",
      data() {
         return {
            container: {},
            products: {},
            provider: {steName: ''}
         }
      },
      mounted() {
         const id = this.$route.params.id;
         axios.get(`/api/arrivals/${id}`, {
            params: {
               with: 'provider,product,unit'
            }
         })
            .then(({data}) => {
               const {provider} = data
               const product = _.cloneDeep(data.product)
               console.log(data)
               this.container = {...data}
               this.products = {...product}
               this.provider = {...provider}
            })
      },
      methods: {
         validContainer(validAll) {
            let URL = `/api/inventories/${arrival_id}/validate`
            const arrival_id = this.$route.params.id
            const products = this.products

            if (validAll)
               URL = `/api/inventories/${arrival_id}/validate-all`

            axios.patch(URL, {products})
               .then(({data}) => {
                  console.log(data)
                  this.$notification.success("La validation a effectuée 👍")
                  this.$router.push({name: 'inventories'})
               })
               .catch(err => {
                  console.log(err.response)
                  this.$notification.error("Erreur de Validation ☠️")
               })
         },
         isOkay(p){
            const product = _.cloneDeep(p);
            return (product.qte_facture * product.rapport_qte) === (product.qte_reception * product.qte_rapport_reception)
         }
      }
   }
</script>

<style scoped>

</style>
