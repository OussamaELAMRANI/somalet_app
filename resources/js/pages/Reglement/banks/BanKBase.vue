<template lang="pug">
   #if_bank
      h2.text-primary.text-uppercase {{bank}}
      .row
         .col-9
            table-layout(theme="bg-primary text-white", :head-table="ifItems", data="payments", empty-text="Pas de payement")
               tr(v-for="p in payments")
                  td {{p.id}}
                  td {{p.payed_at | humane_date}}
                  td
                     p {{p.client | clientName}}
                     p {{p.designation}}

                  td {{(p.operation === 'OPR')? `${p.amount} DH` : ''}}
                  td {{ (p.operation === 'TRS') ? `${p.amount} DH` : ''}}
               tr(class="bg-info text-uppercase font-weight-bold text-white shadow")
                  td(colspan="3") TOTAL
                  td {{debit}} DH
                  td {{credit}} DH
         .col-3.segment.shadow
            router-link.btn.rounded-pill.btn-outline-info.btn-block(:to="{name:'operationMoney'}") Operation
            hr
            h5.text-primary.text-uppercase Filter
               strong {{' ('+bank+')'}}
            hr
            .form-group
               label Date de
               datepicker( :language="fr" :monday-first="true" class="form-control" name="date_picker" v-model="de")

            .form-group
               label Jusqu'à
               datepicker( :language="fr" :monday-first="true" class="form-control" name="date_picker" v-model="au")

            hr
            button.btn-block.btn.btn-outline-primary.rounded-pill(@click="filterByDate") Filter

</template>


<script>
   import TableLayout from "@/components/layouts/TableLayout";
   import moment from 'moment'
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'

   export default {
      name: "BanKBase",
      props: {
         bank: {
            default: 'molsat'
         }
      },
      data() {
         return {
            fr,
            ifItems: ['#', 'Date', 'Client', 'Debit', 'Crédit'],
            payments: [],
            debit: 0,
            credit: 0,
            de: moment(new Date).toDate(),
            au: moment(new Date).toDate(),
            filterDate: {
               'from': moment(new Date).format('YYYY-MM-DD'),
               'to': moment(new Date).format('YYYY-MM-DD'),
            }
         }
      },
      mounted() {
         this.filterByDate()
      },
      methods: {
         filterByDate() {
            if (this.filterDate.from !== null && this.filterDate.to !== null)
               axios.get(`/api/banks/${this.bank}`, {
                  params: {
                     from: this.filterDate.from,
                     to: this.filterDate.to,
                  }
               })
                  .then(({data}) => {
                     const {payments, debit, credit} = data;
                     this.payments = payments
                     this.debit = debit
                     this.credit = credit
                  })
                  .catch(error => console.log(error))
         }
      },
      watch: {
         de: function (d) {
            this.filterDate.from = moment(d).format('YYYY-MM-DD')
         },
         au: function (d) {
            this.filterDate.to = moment(d).format('YYYY-MM-DD')
         },
      },
      components: {TableLayout, Datepicker}
   }
</script>

<style scoped>
   .segment {
      margin: 0;
      border-bottom: 2px solid orange;
   }
</style>
