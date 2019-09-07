<template lang="pug">
   #new_payment
      h2 Nouveau Paiément:
      hr
      .row.justify-content-center
         form.segment.col-10(@submit.prevent="")
            .form-group
               h5.text-primary Livraison N:
                  strong {{' '+$route.params.cmd}}
               hr
               .row.justify-content-center
                  .col-4
                     label Date de paiément
                     datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDate"
                        calendar-button-icon='fa fa-user' name='date_picker' v-model='payed_at' id="payed")
                  .col-4
                     label Montant
                     .input-group
                        input.form-control(type='text' v-model="payment.amount")
                        .input-group-append
                           span.input-group-text DH
               .row.justify-content-center.my-5
                  .col-6
                     p.text-primary.text-center.text-uppercase Mode de paiément
                     payment-type(:types='types' @getType="getType")
               transition(name="deadline")
                  .row.justify-content-center(v-if="payment.type!='ESP'")
                     .col-4
                        .form-group
                           label Date d'échéance :
                           datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDeadline"
                              calendar-button-icon='fa fa-user' name='date_picker' v-model='date_deadline')
               .row.justify-content-end
                  .col-3
                     button.btn.btn-primary(@click="checkout") Ajouter ce paiément


</template>

<script>
   import moment from 'moment'
   import PaymentType from "@/components/Payments/PaymentType";
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'

   export default {
      name: "NewPayment",
      components: {Datepicker, PaymentType},
      data() {
         return {
            fr,
            payment: {
               payed_at:  moment(new Date()).format('YYYY-MM-DD'),
               date_deadline: moment(new Date()).format('YYYY-MM-DD'),
               amount: '',
               type: 'ESP',
            },
            payed_at:  moment().toDate(),
            date_deadline: moment().toDate(),
            types: [
               {id: 'CHQ', h: 'h5', title: "Chéque", isActive: false, img: 'boxes/cheque.svg'},
               {id: 'ESP', h: 'h5', title: "Espace", isActive: true, img: 'boxes/bills.svg'},
               {id: 'EFF', h: 'h5', title: "Effet", isActive: false, img: 'boxes/effet.svg'},
            ],
         }
      },
      methods: {
         getDate(d){
            this.payment.payed_at = moment(d).format('YYYY-MM-DD')
            console.log(moment(d).format('DD/MM/YYYY'))
         },
         getDeadline(d){
            this.payment.date_deadline = moment(d).format('YYYY-MM-DD')
         },
         getType(type) {
            this.payment.type = type
         },
         checkout() {
            const cmd = this.$route.params.cmd;
            const payment = this.payment
            axios.post(`/api/payments/${cmd}`, {payment})
               .then(({data}) => {
                  console.log(data)
                  this.$notification.success('Paiément a été bien ajouté !');
                  if(this.payment.type == 'ESP')
                     this.$router.push({name:'caisse'})
                  else
                     this.$router.push({name:'portfeuille'})
               })
               .catch(err => {
                  console.log(err.response)
               })
         }
      }
   }
</script>

<style scoped>
   .deadline-enter,
   .deadline-leave-to {
      opacity: 0;
      transform: rotateY(50deg);
   }
   .deadline-enter-to,
   .deadline-leave {
      opacity: 1;
      transform: rotateY(0deg);
   }
   .deadline-enter-active,
   .deadline-leave-active {
      transition: opacity, transform 200ms ease-out;
   }
</style>
