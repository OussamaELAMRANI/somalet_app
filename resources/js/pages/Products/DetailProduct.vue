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
      .container(v-if="roles.includes('ADMINE')")
         .row.m-3.segment(v-if="detail.type === 'PF'")
            .col-4
               label Prix d'achat
               input.form-control(type="number" v-model="detail.buy_price")
            .col-4
               label Prix de vente
               input.form-control(type="number" v-model="detail.sell_price")
            .col-4.text-center
               label.text-white Action
               button.btn.btn-block.btn-success(@click="setPrice") Enregister le prix
      .row
         .col-9
            table(class="table table-hover table-striped")
               thead(class="bg-success text-white")
                  tr
                     th #
                     th Numéro
                     th Type
                     th Date
                     th Quantité
                     th Rapport
                     th Total
               tbody
                  tr(v-for="(c, i) in detail.arrivals ")
                     td {{i+1}}
                     td {{c.n_dossier}}
                     td {{c.type}}
                     td {{c.date_facture | humane_date}}
                     td {{c.pivot.qte_reception}} {{showUnit(detail.unit)}}
                     td {{c.pivot.qte_rapport_reception}} {{showUnit(detail.unit, false)}}
                     td(v-html="calcTotal(c.pivot)+' '+showUnit(detail.unit, false)+'(s)'")

         .col-3(v-if="detail.type === 'PF'")
            table(class="table table-hover table-striped text-center")
               thead(class="bg-primary text-white")
                  tr
                     th #
                     th Pointure
                     th (g)
               tbody
                  tr(v-for="(c, i) in detail.sizes ")
                     td {{i+1}}
                     td {{c.size}}
                     td {{c.pivot | getWeight}}
</template>

<script>
import TableLayout from "@/components/layouts/TableLayout";
import store from "@/store";

export default {
   name: "DetailProduct",
   components: {TableLayout},
   data() {
      return {
         roles: store.getters.roles,
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
            sell_price: 0,
            buy_price: 0,
         },
         head_table: ['#', 'Image', 'Reference', 'Designation', 'Stock min', "Type", "Unité", "Catégorie", "Couleur", "Fournisseur"],
      }
   },
   mounted() {
      const id = this.$route.params.id
      axios.get(`/api/products/${id}?with=unit,provider,color,category,arrivals,sizes`)
         .then(({data}) => {
            this.detail = {...data}
         })
         .catch(err => console.log(err.response))
   },
   methods: {
      setPrice() {
         const id = this.$route.params.id
         const {sell_price, buy_price} = this.detail;
         axios.put(`/api/products/${id}/price`, {sell_price, buy_price})
            .then(({data}) => {
               this.$notification.success("Prix a été bien modifier")
            })
            .catch(err => console.log(err.response))
      },
      calcTotal(p) {
         const total = Number(p.qte_reception) * Number(p.qte_rapport_reception)
         return `<strong>${total}</strong>`
      }
   },
   filters: {
      getWeight: function (w) {
         if (!w) return '';
         return w.weight;
      }
   }
}
</script>

<style scoped>

</style>
