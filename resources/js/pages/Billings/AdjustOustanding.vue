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

         template(v-if="['CHEQUE','EFFET'].includes(pType)")
            .col-6
               .form-group
                  label Banque
                  select.form-control(v-model="payment.from_bank")
                     option(:value="null") Indifférent
                     option(v-for="(t,i) in banks" :value="t.id") {{t.company}}

            .col-6
               label Date d'encaissement
               datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDate"
                  calendar-button-icon='fa fa-user' name='date_picker' v-model='payed_at' id="payed")
            .col-6
               .form-group
                  label Date d'échéance :
                  datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDeadline"
                     calendar-button-icon='fa fa-user' name='date_picker' v-model='date_deadline')
            .col-6
               label N (Chéque/Effet)
               .input-group
                  .input-group-append
                     span.input-group-text NUMÉRO
                  input.form-control(type="number", v-model="payment.chq_number")

         .col-12.text-right
            button.btn.btn-primary(@click="adjustNow") Régler maintenant


</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import moment from 'moment'

   export default {
      name: "AdjustOustanding",
      components: {TableLayout, Datepicker},
      data() {
         return {
            payed_at: moment().toDate(),
            date_deadline: moment().toDate(),
            fr,
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
            pType: 'CHEQUE',
            banks: []
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
               this.$router.push('/404')
            });
         axios.get('/api/payments-type')
            .then(({data}) => {
               this.types = data;
            })
            .catch(error => console.log(error));
         axios.get('/api/banks/external')
            .then(({data}) => {
               this.banks = data
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
         },
         getDate(d) {
            this.payment.payed_at = moment(d).format('YYYY-MM-DD');
            console.log(moment(d).format('DD/MM/YYYY'));
         },
         getDeadline(d) {
            this.payment.date_deadline = moment(d).format('YYYY-MM-DD')
         },
      },
      watch: {
         'payment.typed': function (t) {
            if (this.types.length > 0) {
               const p = _.filter(this.types, (p) => {
                  return p.id === t
               })
               this.payment.adjust_by = this.pType = p[0].type;
            }
         }
      }
   }
</script>

<style scoped>

</style>
