<template lang="pug">
   #transfer_money
      h4.text-secondary.text-uppercase Fait une opperation
      hr
      .container
         .row.justify-content-center
            .col-9.segment
               form(@submit.prevent="transferMoney")
                  .form-group.row.justify-content-center
                     .col-9
                        label Transférer depuis
                        select.form-control(v-model="payment.in_bank")
                           option(v-for="b in banksIN" :value="b.id") {{b.name}}

                  .form-group.row.justify-content-center
                     .col-4
                        label Montant (ESP)
                        .input-group
                           input.form-control(type="number", placeholder="1000... DH", v-model.number="payment.amount")
                           .input-group-append
                              span.input-group-text DH


                     .col-5
                        label Designation
                        input.form-control(type="text" v-model="payment.designation")
                  .form-group.row.justify-content-end
                     .col-12
                        hr
                     .col-2
                        button.btn.btn-primary Transférer

</template>


<script>
   export default {
      name: "OperationFromBank",
      data() {
         return {
            banksIN: [],
            payment: {
               amount: 0,
               in_bank: 0,
               operation: 'OPR',
               designation: ''
            }
         }
      },
      mounted() {
         axios.get('/api/banks/internals')
            .then(({data}) => {
               this.banksIN = data
            }).catch(error => console.log(error))
      },
      methods: {
         transferMoney() {
            const payment = this.payment;
            axios.post('/api/payments/operation', {payment})
               .then(({data}) => {
                  // console.log(data)
                  this.$router.push({name:'banks'})

               }).catch(error => console.log(error))

         }
         }

   }
</script>

<style scoped>

</style>
