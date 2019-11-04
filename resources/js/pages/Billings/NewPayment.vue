<template lang="pug">
   #new_payment
      h2 Nouveau Paiément:
      hr
      .row.justify-content-center
         form.segment.col-10(@submit.prevent="")
            .form-group
               h5.text-primary CLIENT :
                  strong {{' '+client.nom}} ({{client.id}})
               hr
               .row.justify-content-center.my-3
                  .col-6
                     .form-group
                        label Mode de paiément
                        select.form-control(v-model="payment.typed")
                           option(:value="null" disabled) Selectionnez ici
                           option(v-for="(t,i) in types" :value="t.id") {{t.type}}
               .row.justify-content-center
                  .col-12
                     hr
                  .col-4
                     label Date d'encaissement
                     datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDate"
                        calendar-button-icon='fa fa-user' name='date_picker' v-model='payed_at' id="payed")
                  .col-4
                     label Montant
                     .input-group
                        input.form-control(type='number' v-model.number="payment.amount")
                        .input-group-append
                           span.input-group-text DH

                     //payment-type(:types='types' @getType="getType")

                  .col-4
                     .form-group
                        label Banque
                        select.form-control(v-model="payment.from_bank")
                           option(:value="null") Indifférent
                           option(v-for="(t,i) in banks" :value="t.id") {{t.company}}
                  .col-4
                     .form-group
                        label Date d'échéance :
                        datepicker.form-control(:language='fr' :monday-first='true' format="dd/MM/yyyy" @selected="getDeadline"
                           calendar-button-icon='fa fa-user' name='date_picker' v-model='date_deadline')
                  .col-4
                     .form-group
                        label Numéro
                        input.form-control(type="text" placeholder="Numero de CHQ/EFF", v-model="payment.chq_number")
                  .col-4
                     .form-group
                        label Désignation
                        input.form-control(type="text" placeholder="Remarque ...", v-model="payment.designation")

               hr
               .row.justify-content-end
                  .col-3.text-right
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
                    payed_at: moment(new Date()).format('YYYY-MM-DD'),
                    date_deadline: moment(new Date()).format('YYYY-MM-DD'),
                    amount: 0,
                    typed: null,
                    from_bank: null,
                    chq_number: '',
                    designation: '',
                },
                payed_at: moment().toDate(),
                date_deadline: moment().toDate(),
                types: [],
                client: [],
                banks: [],
            }
        },
        mounted() {
            const id = this.$route.params.cmd;
            axios.get(`/api/clients/${id}`)
                .then(({data}) => {
                    this.client = data;
                })
                .catch(error => {
                    console.log(error);
                    this.$router.push('/404')
                    this.$notification.error(error.response.data);

                });
            axios.get('/api/payments-type')
                .then(({data}) => {
                    this.types = data;
                })
                .catch(error => console.log(error));
            axios.get('/api/banks/external')
                .then(({data}) => {
                    this.banks = data;
                })
                .catch(error => console.log(error))
        },
        methods: {
            getDate(d) {
                this.payment.payed_at = moment(d).format('YYYY-MM-DD')
                console.log(moment(d).format('DD/MM/YYYY'))
            },
            getDeadline(d) {
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

                        // if(this.payment.type == 'ESP')
                        this.$router.push({name: 'listClient'})
                        // else
                        //    this.$router.push({name:'portfeuille'})
                    })
                    .catch(err => {
                        console.log(err.response)
                    })
            }
        },
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
