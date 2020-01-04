<template lang="pug">
   #adjust
      h4 Régler l'impayé N: ({{impayed_id}})
      hr
      .row
         .col-10.segment
            form(@submit.prevent="transferMoney")
               .form-group.row.justify-content-center
                  //.col-6
                     label Débiter la banque:
                     select.form-control(v-model="payment.in_bank")
                        option(v-for="b in banksIN" :value="b.id") {{b.name}}
                  .col-6
                     label Mode de paiément
                     select.form-control(v-model="payment.typed")
                        option(:value="null" disabled) Selectionnez ici
                        option(v-for="(t,i) in types" :value="t.id") {{t.type}}
               .form-group.row.justify-content-center
                  .col-6
                     label Montant (ESP)
                     .input-group
                        input.form-control(type="number", placeholder="1000... DH", v-model.number="payment.amount")
                        .input-group-append
                           span.input-group-text DH

                  .col-6(v-if="['CHEQUE','EFFET'].includes(pType) ")
                     label N (Chéque/Effet)
                     .input-group
                        .input-group-append
                           span.input-group-text NUMÉRO
                        input.form-control(type="number", v-model="payment.chq_number")


                  .col-12
                     label Designation
                     input.form-control(type="text" v-model="payment.designation")
               .form-group.row.justify-content-end
                  .col-12
                     hr
                  .col-2
                     button.btn.btn-primary Transférer


</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      name: "AdjustOustanding",
      components: {TableLayout},
      data() {
         return {
            impayed_id: null,
            banksIN: [],
            types: [],
            payment: {
               amount: 0,
               in_bank: 0,
               operation: 'PYM',
               designation: '',
               chq_number: '',
               typed: null,
               adjust_by: null,
               valid: 0 // Just make Trace before Validation
            },
            pType: null

         }
      },
      mounted() {
         this.impayed_id = this.$route.params.id;
         axios.get(`/api/payments/${this.impayed_id}/isoutstanding`)
            .then(({data}) => {
               this.types = data;
            })
            .catch(error => console.log(error));
         axios.get('/api/payments-type')
            .then(({data}) => {
               this.types = data;
            })
            .catch(error => console.log(error));
         axios.get('/api/banks/internals')
            .then(({data}) => {
               this.banksIN = data
            }).catch(error => console.log(error))

      },
      watch: {
         'payment.typed': function (t) {
            const p = _.filter(this.types, (p) => {
               return p.id === t
            })
            this.pType = p[0].type
         }
      }
   }
</script>

<style scoped>

</style>
