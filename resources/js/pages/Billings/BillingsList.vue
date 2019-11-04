<template lang="pug">
   #billing_list
      h3 Port feuille mensuelle pour valider
      .row
         .col-10
            hr
         .col-2
            .form-group
               label Choisir l'année
               input.form-control(type="number" v-on:keydown.enter="setYear" placeholder="2019 ou 2020 ...")
      .row
         .col-2(v-for="(m, i) in months")
            wallet-month(:year="year", :month="m", :go="{name:'monthBilling', params:{year, month:i+1}}")

</template>

<script>
    import WalletMonth from "@/components/Bills/WalletMonth";
    import moment from 'moment'

    export default {
        name: "BillingsList",
        data() {
            return {
                months: ['jan', 'fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                year: moment().toDate().getFullYear()
            }
        },
        methods: {
            setYear(e) {
                const y = e.target.value;
                this.year = y
            }
        },
        components: {WalletMonth}
    }
</script>

<style scoped>

</style>
