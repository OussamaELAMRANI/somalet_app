<template lang="pug">
   #money-bag
      h2.text-secondary.text-uppercase Caisse
      hr
      table-layout(:head-table="items", :data="checkout", empty-text="Pas d'agent $ !", theme="bg-primary small text-white")
         tr(v-for=" (p, i) in checkout")
            td {{i+1}}
            td {{p.payed_at | humane_date}}
            td {{p.amount + ' DH'}}
            td
               router-link(:to="{name:'movementClient', params:{id:p.client.id}}") ({{p.client.id}}) {{p.client.nom }}
            td
               button.btn-outline-primary.btn.btn-sm
                  i.fa.fa-university
                  | {{' Molsat'}}
               button.btn-outline-success.btn.btn-sm
                  i.fa.fa-university
                  | {{' IF'}}
</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";

    export default {
        name: "Caisse",
        data() {
            return {
                items: ['#', 'Date de payement', 'Montant', 'Client', 'Verser vers'],
                checkout: null
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
