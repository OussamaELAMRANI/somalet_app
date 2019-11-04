<template lang="pug">
   #money-bag
      h2.text-secondary.text-uppercase Caisse
      hr
      .row
         .col-9
            table.table-striped.table.table-bordered.text-center
               thead
                  tr
                     th #
                     th Date
                     th Designation
                     th Débit
                     th Credit

               tbody
                  tr(v-for=" (p, i) in checkout" :class=" (p.to_bank !== null)? 'bg-success text-white':null ")
                     th {{i+1}}
                     td {{p.payed_at | humane_date}}
                     td {{p.client | getName}} {{p.designation}}
                     td {{ (p.operation === 'TRS') ?  p.amount + ' DH': null}}
                     td {{ (p.operation === 'PYM') ? p.amount + ' DH' : null}}
                     td(v-if="p.to_bank !== null").shadow {{p.to_bank | getBank}}
               tfoot
                  tr
                     td(colspan="3") TOTAL
                     td {{debit}} DH
                     td {{credit}} DH
                     td.font-weight-bolder.shadow-lg {{credit - debit}} DH
         .col-3
            router-link.btn.btn-outline-primary.btn-block.mb-2(:to="{name:'transferMoney'}") TRANSFER
            .segment.my-0
               h4.text-right.text-uppercase.text-secondary Filter
               hr

</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";

    export default {
        name: "Caisse",
        data() {
            return {
                // items: ['#', 'Date de payement', 'Montant', 'Client', 'Vairser vers'],
                checkout: null,
                debit: 0,
                credit: 0,
            }
        },
        mounted() {
            this.getCheckout()
        },
        methods: {
            getCheckout() {
                axios.get('/api/payments/checkout')
                    .then(({data}) => {
                        this.checkout = data
                    })
                    .catch(({response}) => console.log(response))

            },
            sendToBank(payment_id, bank) {
                axios.post(`/api/banks/${bank}`, {payment_id})
                    .then(({data}) => {
                        this.checkout = this.checkout.filter((m) => {
                            return m.id !== payment_id
                        })
                        this.$notification.success(`Vous êtes bien versé le mantant à la banque: ${bank}`)
                    })
                    .catch(({response}) => console.log(response))
            }
        },
        filters: {
            getName: function (client) {
                return (client === null) ? null : client.nom + ' (' + client.id + ')';
            },
            getBank: function (bank) {
                return (bank === null) ? null : bank.name;
            },
        },
        watch: {
            checkout: function (c) {
                let debit = 0, credit = 0;
                // 'PYM', 'TRS', 'OPR'
                c.forEach(v => {
                    if (v.operation === 'PYM')
                        credit += v.amount;
                    else if (v.operation === 'TRS')
                        debit += v.amount;
                })
                this.debit = debit;
                this.credit = credit;
            }
        },
        components: {TableLayout}
    }
</script>

<style scoped>
   td button {
      margin-right: 4px;
   }
</style>
