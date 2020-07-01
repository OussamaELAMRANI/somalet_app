<template lang="pug">
   #if_bank
      alert-modal(title="Attention", id="bitch")
         template(v-slot:content)
            h4.text-center.text-primary Voulez vous vraiment designer ce chéque/effet comme impayé ?
         template(v-slot:btn)
            button.btn.btn-success(@click="makeOut") IMPAYÉ

      h2.text-primary.text-uppercase Banque {{bank}}
      .row
         .col-9
            table-layout(theme="bg-primary text-white", :head-table="ifItems", data="payments", empty-text="Pas de payement")
               tr(v-for="p in payments")
                  td.align-middle {{p.id}}
                  td.align-middle {{p.payed_at | humane_date}}
                  td.align-middle
                     p.badge.badge-info {{getType(p.typed) | getTypeName}}
                  td.align-middle
                     p {{p.client | clientName}}
                     p
                        strong {{(p.chq_number == null)? null : `N: ${p.chq_number}`}}
                     p {{p.designation}}

                  td.align-middle {{(p.operation === 'OPR' || !p.done )? `${p.amount} DH` : ''}}
                  td.align-middle {{ ((p.operation === 'TRS' || p.operation === 'PYM') && p.done) ? `${p.amount} DH` : ''}}
                  td.align-middle(v-if="p.done && p.operation === 'PYM' && p.state !== 'IGN'")

                     button.btn.btn-block.btn-link(v-if="hasImpayed(p.typed)" @click="paymentId=p.id" data-target="#bitch" data-toggle="modal") Impayé
                  td.align-middle(v-else-if="p.operation === 'TRS'")
                     p.badge-warning TRANSFER
                  td.align-middle(v-else-if="p.state === 'IGN'")
                     p.badge-danger IMPAYÉ
                  td.align-middle(v-else-if="p.state === 'JST'")
                     p.text-danger {{(p.adjust_by) ? 'Réglé par: '+p.adjust_by :'EN ATTENT'}}
                  td.align-middle(v-else)
                     p.badge-success CHARGES
               tr(class="bg-info text-uppercase font-weight-bold text-white shadow")
                  td(colspan="4") TOTAL
                  td {{debit}} DH
                  td {{credit}} DH
                  td {{credit-debit | fixed_two}} DH
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
   import AlertModal from "@/components/Modals/AlertModal";

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
            ifItems: ['#', 'Date', 'Type', 'Détail', 'Debit', 'Crédit', 'ETAT'],
            payments: [],
            debit: 0,
            credit: 0,
            de: moment(new Date).toDate(),
            au: moment(new Date).toDate(),
            filterDate: {
               'from': moment(new Date).format('YYYY-MM-DD'),
               'to': moment(new Date).format('YYYY-MM-DD'),
            },
            types: [],
            pType: '',
            paymentId: null
         }
      },
      mounted() {
         this.filterByDate()
         axios.get('/api/payments-type')
            .then(({data}) => {
               this.types = data;
            }).catch(error => console.log(error));
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
         },
         hasImpayed(t) {
            let p = this.getType(t)
            p = p[0].type

            return ['CHEQUE', 'EFFET'].includes(p)
         },
         getType(t) {
            return _.filter(this.types, (p) => {
               return p.id === t
            })
         },
         makeOut() {
            const id = this.paymentId
            axios.put(`/api/payments/${id}/impaye`)
               .then(({data}) => {
                  // this.types = data;
                  // this.$router.push({name: 'banks'})
                  location.reload();
               }).catch(error => console.log(error));
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
      filters: {
         getTypeName: function (t) {
            return (t.length == 0) ? null : t[0].type
         }
      },
      components: {AlertModal, TableLayout, Datepicker}
   }
</script>

<style scoped>
   .segment {
      margin: 0;
      border-bottom: 2px solid orange;
   }
</style>
