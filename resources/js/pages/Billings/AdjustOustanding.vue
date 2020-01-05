<template lang="pug">
   #adjust
      .row
         .col-2
            h4 Régler l'impayé
         .col-4
            hr
         .col-4
            h5.text-info.text-uppercase
               strong {{' '+payment.designation}}

      .row.segment
         .col-6
            .form-group
               label Mode de paiément
               p.card.p-2.bg-light(class="") {{payment.types | paymentMode}}
         .col-6
            .form-group
               label Banque
               p.card.p-2.bg-light(class="") {{payment.form_bank | getBank}}

         .col-6
            .form-group
               label Date d'encaissement
               p.card.p-2.bg-light(class="") {{payment.payed_at | humane_date}}

         .col-6
            .form-group
               label Date d'écheancé
               p.card.p-2.bg-light(class="") {{payment.date_deadline | humane_date}}

         .col-6
            label Montant
            p.card.p-2.bg-light(class="") {{payment.amount }} DH
         .col-6
            .form-group
               label Observation
               p.card.p-2.bg-light(class="") {{payment.designation}}


      .row.segment
         //.col-6
            label Débiter la banque:
            select.form-control(v-model="payment.in_bank")
               option(v-for="b in banksIN" :value="b.id") {{b.name}}
         .col-6
            label Réglé par:
            select.form-control(v-model="payment.typed")
               option(:value="null" disabled) Selectionnez ici
               option(v-for="(t,i) in types" :value="t.id") {{t.type}}
         .col-6(v-if="['CHEQUE','EFFET'].includes(pType) ")
            label N (Chéque/Effet)
            .input-group
               .input-group-append
                  span.input-group-text NUMÉRO
               input.form-control(type="number", v-model="payment.chq_number")

         .col-12.text-right
            button.btn.btn-primary(@click="adjustNow") Transférer


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
               types: null,
               form_bank: null,
               adjust_by: null,
               valid: 0 // Just make Trace before Validation
            },
            pType: null,
         }
      },
      mounted() {
         const id = this.$route.params.id;
         axios.get(`/api/payments/${id}/isoutstanding`)
            .then(({data}) => {
               this.payment = data;
            })
            .catch(error => {
               console.log(error)
               this.$router.push('404')
            });
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
      methods: {
         adjustNow() {
            const {id} = this.payment;
            axios.put(`/api/payments/${id}/adjust`,
               {
                  payment: this.payment
               }
            ).then(({data}) => {
               console.log(data)
               this.$router.push({
                  name: 'outstandingPayments'
               })
            })
         }
      },
      watch: {
         'payment.typed': function (t) {
            const p = _.filter(this.types, (p) => {
               return p.id === t
            })
            this.payment.adjust_by = p[0].type
         }
      }
   }
</script>

<style scoped>

</style>
