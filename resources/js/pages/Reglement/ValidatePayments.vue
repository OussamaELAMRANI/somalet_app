<template lang="pug">
   #validate_payments
      h3.text-primary.font-weight-light.text-right VALIDATION PAIÉMENTS $
      hr
      .row.justify-content-center
         .col-10
            table.table.shadow.table-striped.text-center
               thead.bg-primary
                  tr
                     th Date
                     th Client
                     th Type
                     th Numéro
                     th MONTANT
                     th Designation
                     th Actions
               tbody
                  tr(v-for="(p,i) in payments")
                     td.align-middle {{p.payed_at | humane_date}}
                     td.align-middle.font-weight-bold ({{p.client_id}}) {{p.nom}}
                     td.align-middle {{p.type}}
                     td.align-middle {{p.chq_number}}
                     td.align-middle {{p.amount}} DH
                     td.align-middle
                        p {{p.designation}}
                        p.font-weight-bold {{p.form_banque | getBank}}
                     td.align-middle
                        button.btn.btn-sm.btn-success(@click="validatePayment(p.id, p.type)") Valider
               tfoot(v-if="payments.length >0")
                  tr
                     td(colspan="7").text-right
                        button.btn.btn-primary.text-white(@click="validatePayments") Valider Tout

         .col-8
            .segment.m-0.shadow-lg
               table.table.mx-0.text-center
                  thead.bg-primary.text-white
                     tr
                        th TYPE
                        th MONTANT (DH)
                  tbody
                     tr(v-for="(p,i) in total")
                        td.text-uppercase {{i}}
                        td {{p + ' '}}
                           small DH
                  tfoot.segment.shadow
                     tr
                        th TOTAL
                        th {{sendTotal}} DH

</template>

<script>
    export default {
        name: "ValidatePayments",
        data() {
            return {
                payments: [],
                ESPECE: [],
                CHEQUE: [],
                EFFET: [],
                BON: [],
                AVOIR: [],
                REMISE: [],
                total: {
                    espece: 0,
                    cheque: 0,
                    effet: 0,
                    bon: 0,
                    remise: 0,
                    avoir: 0,
                }

            }
        },
        mounted() {
            axios.get('/api/payments/invalid')
                .then(({data}) => {
                    const {payments, grouped} = data;
                    const {ESPECE, CHEQUE, EFFET, AVOIR, REMISE, BON} = grouped;

                    this.payments = payments;
                    this.ESPECE = ESPECE;
                    this.CHEQUE = CHEQUE;
                    this.EFFET = EFFET;
                    this.AVOIR = AVOIR;
                    this.REMISE = REMISE;
                    this.BON = BON;
                })
        },
        methods: {
            validatePayment(id, type) {
                axios.post(`/api/payments/validate`, {ids: [id]})
                    .then(({data}) => {
                        console.log(data)
                        this.payments = this.payments.filter(p => p.id != id)

                        switch (type) {
                            case 'ESPECE':
                                this.ESPECE = this.ESPECE.filter(p => p.id !== id);
                                break;

                            case 'CHEQUE':
                                this.CHEQUE = this.CHEQUE.filter(p => p.id !== id);
                                break;
                            case 'EFFET':
                                this.EFFET = this.EFFET.filter(p => p.id !== id);
                                break;
                            case 'AVOIR':
                                this.AVOIR = this.AVOIR.filter(p => p.id !== id);
                                break;
                            case 'REMISE':
                                this.REMISE = this.REMISE.filter(p => p.id !== id);
                                break;
                            case 'BON':
                                this.BON = this.BON.filter(p => p.id !== id);
                                break;
                            default:
                                break;
                        }

                    })
                    .catch(error => console.log(error.response))
            },
            validatePayments() {
                const ids = this.payments.map((p) => p.id)
                axios.post(`/api/payments/validate`, {ids: ids})
                    .then(({data}) => {
                        this.payments = this.payments.filter(p => !ids.includes(p.id))
                        this.clearAll()
                    })
                    .catch(error => console.log(error.response))
            },
            clearAll() {
                this.ESPECE = []
                this.BON = []
                this.REMISE = []
                this.CHEQUE = []
                this.AVOIR = []
                this.EFFET = []
            }
        },
        watch: {
            CHEQUE: function (v) {
                let total = 0;
                total = _.sumBy(v, (c) => c.amount);
                this.total = {...this.total, ...{cheque: total}}
            },
            ESPECE: function (v) {
                let total = 0;
                total = _.sumBy(v, (c) => c.amount);
                this.total = {...this.total, ...{espece: total}}
            },
            EFFET: function (v) {
                let total = 0;
                total = _.sumBy(v, (c) => c.amount);
                this.total = {...this.total, ...{effet: total}}
            },
            AVOIR: function (v) {
                let total = 0;
                total = _.sumBy(v, (c) => c.amount);
                this.total = {...this.total, ...{avoir: total}}
            },
            REMISE: function (v) {
                let total = 0;
                total = _.sumBy(v, (c) => c.amount);
                this.total = {...this.total, ...{remise: total}}
            },
            BON: function (v) {
                let total = 0;
                total = _.sumBy(v, (c) => c.amount);
                this.total = {...this.total, ...{bon: total}}
            },
        },
        filters: {
            getBank: (obj) => {
                console.log(obj)
                if (obj == null)
                    return null
                else
                    return obj.name;
            }
        },
        computed: {
            sendTotal() {
                let tt = 0;
                Object.keys(this.total).forEach(k => {
                    tt += this.total[k]
                })
                return tt;
            }
        }

    }
</script>

<style scoped>
   .segment {
      border-top: 2px solid orange;
   }
</style>
