<template lang="pug">
    #stock_alert
        .container.my-4
            h3.text-danger ALERTS
            hr
            .row
                .col-9
                    table.table.table-bordered.table-striped
                        thead.bg-danger.text-white
                            tr
                                th #
                                th Reference
                                th Designation
                                th Unité
                                th Quantite Réel
                                th Quantite d'Alert
                        tbody
                            tr(v-for="p in products")
                                td 1
                                td {{p.reference}}
                                td {{p.name}}
                                td {{p.unity}}
                                td {{p.totalQte}}
                                td {{p.alert}}


</template>

<script>
    export default {
        name: "StockAlert",
        data(){
            return {
                products:[]
            }
        },
        mounted() {
            axios.get('/api/inventories/alert')
                .then(({data})=> this.products = data)
        }
    }
</script>

<style scoped>

</style>