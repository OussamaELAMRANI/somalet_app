<template lang="pug">
   #client_detail.container
      .row(v-if="client===null")
         div(class="col-2")
            SelfBuildingSquareSpinner( :animation-duration="6000" :size="40" color="#ff1d5e")
      table-layout(:head-table="hItems", :data="client" v-else)
         tr
            td {{client === null ? null: client.lastName}} {{client === null ? null: client.firstName}}
            td {{client === null ? null: client.numTva}}
            td {{client === null ? null: client.nSiret}}
            td.font-italic {{client === null ? null: client.address}},  {{client === null ? null: client.city}} {{client === null ? null: client.country}}

      .row.justify-content-center
         .col-3
            p.text-primary.lead
               i.fa.fa-voicemail
               span {{client === null ? null: client.phone}}
         .col-3
            p.text-primary.lead
               i.fa.fa-phone
               | {{client === null ? null: client.cell}}
         .col-4
            p.text-primary.lead
               i.fa.fa-envelope
               | {{client === null ? null: client.email}}
      hr

      h4.text-secondary.text-uppercase Remises
      .row.justify-content-center.align-items-center
         .col-3
            .form-group
               label Reference
               input.form-control(type="text")
         .col-6
            .form-group
               label Reference
               input.form-control(type="text")
         .col-3
            button.btn-success.btn
               i.fa.fa-plus
               | {{' Ajouter une Remise'}}

      .row(v-if="discount===[]")
         div(class="col-2")
            SelfBuildingSquareSpinner( :animation-duration="6000" :size="40" color="#ff1d5e")
      table-layout(:head-table="hRemise", theme='bg-success text-white', :data="client")
         tr(v-for="(p,i) in discount")
            td {{p.reference}}
            td {{p.discount}}
            td
               button.btn.btn-sm.btn-primary.mx-1(@click="modifyDiscount(i)")
                  i.fa.fa-pen
               button.btn.btn-sm.btn-danger.mx-1(@click="deleteDiscount(i)")
                  i.fa.fa-trash
      h4.text-uppercase.text-primary Mouvements client
      hr
      .row.justify-content-center.text-center
         .col-4
            button.btn.btn-primary
               i.fa.fa-list.mx-2
               |  Afficher les mouvements en detail
</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";
   import SelfBuildingSquareSpinner from "epic-spinners/src/components/lib/SelfBuildingSquareSpinner";

   export default {
      name: "ClientDetail",
      components: {SelfBuildingSquareSpinner, TableLayout},
      data() {
         return {
            client: null,
            hItems: ['Nom Prénom', 'TVA', 'SIRET', 'Adresse'],
            hRemise: ['Produit', 'Remise en (DH) ', 'Actions'],
            discount: [],
         }
      },
      created() {
         const id = this.$route.params.id
         axios.get(`/api/clients/${id}`)
            .then(({data}) => {
               const {discounting} = data
               this.client = data
               this.discount = discounting
            })
            .catch(({response}) => {
               console.log(response)
            })
      },
      methods: {
         modifyDiscount(reference) {

         },
         deleteDiscount(reference) {
            this.discount = _.filter(this.discount, d => d['reference'] !== reference)
         },
      },
      computed: {}
   }
</script>

<style scoped>
   p > i {
      margin-right: 8px;
   }
</style>
