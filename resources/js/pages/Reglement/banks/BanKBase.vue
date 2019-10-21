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
                     router-link(:to="{name:'movementClient', params:{id:p.client.id}}") ({{p.client.id}}) {{p.client.nom }}

                  td {{p.amount}} DH
         .col-3.segment.shadow
            h5.text-primary.text-uppercase Filter
               strong {{' ('+bank+')'}}
            hr
            .form-group
               label Date de
               input.form-control.rounded-pill(type="text")

            .form-group
               label Jusqu'à
               input.form-control.rounded-pill(type="text")

            hr
            button.btn-block.btn.btn-outline-primary.rounded-pill Filter

</template>


<script>
    import TableLayout from "@/components/layouts/TableLayout";

    export default {
        name: "BanKBase",
        props: {
            bank: {
                default: 'molsat'
            }
        },
        data() {
            return {
                ifItems: ['#', 'Date', 'Client', 'Montant'],
                payments: []
            }
        },
        mounted() {
            this.getIFpayments(this.bank)
        },
        methods: {
            getIFpayments(by) {
                axios.get(`/api/banks/${by}`)
                    .then(({data}) => {
                        const {payments} = data;
                        this.payments = payments
                    })
                    .catch(error => console.log(error))
            }
        },
        components: {TableLayout}
    }
</script>

<style scoped>
   .segment {
      margin: 0;
      border-bottom: 2px solid orange;
   }
</style>
