<template lang="pug">
   #valid_container.container
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
      hr
      table.table.text-center
         thead.bg-primary.text-white
            tr
               th Produit
               th Quantité
               th Rapport
               th Total
               th Unité
               th Prix Unitaire
               th Prix de Vente
         tbody
            tr(v-for="(p,i) in container.product")
               td {{p.name}}
               td
                  .input-group-sm.input-group
                     input.form-control.form-control-sm(type="number" placeholder="Quantité ...", v-model.number="products[i].pivot.qte_validation")
                     .input-group-prepend
                        span.input-group-text.bg-primary.text-white.font-weight-bolder {{p.pivot.qte_reception}}
               td
                  .input-group-sm.input-group
                     input.form-control.form-control-sm(type="number" placeholder="Rapport ...", v-model.number="products[i].pivot.qte_rapport_validation")
                     .input-group-prepend
                        span.input-group-text.bg-primary.text-white.font-weight-bolder {{p.pivot.qte_rapport_reception}}
               td(class="text-primary") {{p.pivot.qte_rapport_reception * p.pivot.qte_reception}}
               td {{p.unit.name}}
               td
                  input.form-control.form-control-sm(type="number" placeholder="Modifier", v-model.number="products[i].pivot.price_unit_ht")
               td
                  input.form-control.form-control-sm(type="number", placeholder="Prix de Vente" v-model.number="products[i].pivot.sell_price")
         tfoot
            tr
               td.text-right(colspan="8")
                  //button.btn.btn-outline-primary.mx-1(@click="validContainer(false)") Valider le Contenaire
                  button.btn.btn-outline-success.mx-1(@click="validContainer(true)") Valider tout

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

                if(validAll)
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
            }
        }
    }
</script>

<style scoped>

</style>
