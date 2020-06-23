<template lang="pug">
   #canceled_orders
      big-title(title="RETOUR DE MARCHANDISE")
      .row
         .col-9
            table-layout(:head-table="tableItems", theme="bg-primary text-white small"
               empty-text='Pas de commande', :data='orders')
               tr(v-for='(o,i) in orders')
                  td {{i+1}}
                  td {{o.cmd_number}}
                  td {{o.date_cmd | humane_date}}
                  td {{o.canceled_date | humane_date}}
                  td {{o.client.lastName}} {{o.client.firstName}}
                  td {{o.canceled_description}}
                  td
                     p(v-if="o.validate_canceled").text-success.font-weight-bold VALIDE
                     p(v-else).text-danger.font-weight-bold INVALID
                  td
                     router-link(:to="{name:'detailOrder',params:{id:o.cmd_number}}").mx-2
                        i.fa.fa-list
                     button.btn.btn-outline-info.shadow.rounded-pill.mx-2(@click="toPrint(o.cmd_number)")
                        i.fa.fa-print

                     button.btn.btn-success.shadow.rounded-pill.my-1(@click="validateReturn(o.id)" v-if="roles.includes('ADMINE')")
                        i.fa.fa-check
         .col-3.segment.tall
            h5.text-primary.text-uppercase Filter Par
            hr
            .custom-control.custom-switch
               input.custom-control-input(type="checkbox" id="customSwitch1" v-model="isValid")
               label.custom-control-label(for="customSwitch1") Annulation {{isValid ? null : 'no ' }} Valide
            select-date(@sendDate="getFromDate" title="De" key="DE")
            select-date(@sendDate="getToDate" title="Jusqu'a" key="TO")
            hr
            button.rounded-pill.btn-block.btn-outline-primary.btn.text-uppercase(@click='filterNow')
               | filter
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import TableLayout from "@/components/layouts/TableLayout";
   import store from '@/store'
   import SelectDate from "@/components/layouts/SelectDate";

   export default {
      name: "CanceledOrders",
      components: {SelectDate, TableLayout, BigTitle},
      data() {
         return {
            tableItems: ['#', 'Livraison', 'Date', 'Date de retour', 'Client', 'Déscription', 'Status', 'Actions'],
            orders: null,
            roles: store.getters.roles,
            dateFrom: '',
            dateTo: '',
            isValid: false ,
         }
      },
      mounted() {
         axios.get('/api/orders/canceled', {
            params: {
               with: 'client'
            }
         })
            .then(({data}) => {
               this.orders = data;
            })
            .catch(err => console.log(err))
      },
      methods: {
         toPrint(id) {
            this.$router.push({
               name: 'printer',
               params: {
                  id,
                  type: 'order',
                  url: `/api/orders/${id}`
               }
            })
         },
         validateReturn(id) {
            axios.post(`/api/orders/validate-canceled/${id}`)
               .then(({data}) => {
                  _.forEach(this.orders, (arr) => {
                     if (arr.id === id)
                        arr.validate_canceled = 1
                  });
                  this.$notification.success("Bien Valider ce retour de marchandise")

                  console.log(data)
               })
               .catch(error => console.log(error.response))
         },
         getFromDate(d) {
            this.dateFrom = d
         },
         getToDate(d) {
            this.dateTo = d
         },
         filterNow() {
            this.orders = null

            axios.get('/api/orders/canceled', {
               params: {
                  with: 'client',
                  'from': this.dateFrom,
                  'to': this.dateTo,
                  valid: (this.isValid) ? 1 : 0
               }
            })
               .then(({data}) => {
                  this.orders = data
                  console.log(data)
               })
               .catch(err => console.log(err.response))
         },

      }
   }
</script>

<style scoped>

</style>
