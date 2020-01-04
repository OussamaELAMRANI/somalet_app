<template lang="pug">
   #UpDeadline
      h4 MODIFIER LA DATE D'ECHEANCE
      hr
      .row.segment
         .col-6
            .form-group
               label Mode de paiément
               p.card.p-2.bg-light(class="") {{payment.types | paymentMode}}
         .col-6
            .form-group
               label Date d'encaissement
               p.card.p-2.bg-light(class="") {{payment.payed_at | humane_date}}
         .col-6
            .form-group
               label Banque
               p.card.p-2.bg-light(class="") {{payment.form_bank | getBank}}

         .col-6
            label Montant
            p.card.p-2.bg-light(class="") {{payment.amount }} DH
         .col-6
            .form-group
               label Observation
               p.card.p-2.bg-light(class="") {{payment.designation}}

         .col-6
            label Date d'encaissement
            datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDate"
               calendar-button-icon='fa fa-user' name='date_picker' v-model='payment.date_deadline' id="payed")
         .col-12
            hr
         .col-12.text-right
            button.btn.btn-success(@click="upDeadline") Modifier

</template>

<script>
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import moment from 'moment'

   export default {
      name: "UpdateDeadline",
      components: {Datepicker},
      data() {
         return {
            fr,
            payment: {
               types:null,
               payed_at:null,
               form_bank:null,
            },
            date_deadline: null
         }
      },
      mounted() {
         this.getPayment(this.$route.params.id)
      },
      methods: {
         getPayment(id) {
            axios.get(`/api/payments/${id}`)
               .then(({data}) => {
                  this.payment = data
               }).catch(error => {
               console.log(error);
               this.$router.push('/404')
            })
         },
         getDate(d) {
            this.date_deadline = moment(d).format('YYYY-MM-DD')
         },
         upDeadline() {
            const id = this.$route.params.id;
            if (this.date_deadline !== null)
               axios.put(`/api/payments/${id}/deadline`,
                  {
                     date_deadline: this.date_deadline
                  }
               )
                  .then(({data}) => {
                     this.$router.push({name: 'portfeuille'})
                  }).catch(error => console.log(error))
         }
      },

   }
</script>

<style scoped>

</style>
