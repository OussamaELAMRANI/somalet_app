<template lang="pug">
   #orders_list
      delete-action(:url="deleteURI" @isDeleted="refreshOrdersList")
      router-link(:to="{name:'newOrder'}").btn.btn-sm.btn-outline-success
         i.fa.fa-backward
         | {{' Nouveau Command'}}
      big-title(title="Liste des commandes !")
      .row
         .col-9
            table-layout(:head-table="tableItems", theme="bg-primary text-white small" empty-text='Pas de commande', :data='orders')
               tr(v-for='(o,i ) in orders')
                  td {{i+1}}
                  td {{o.cmd_number}}
                  td {{o.date_cmd | humane_date}}
                  td {{o.client.lastName}} {{o.client.firstName}}
                  td
                     template(v-if="o.status === 'OUT'")
                        button.btn.btn-sm.btn-outline-success.shadow.rounded-pill.mx-1(@click="toPrint(o.cmd_number)")
                           i.fa.fa-print
                     template(v-else)
                        router-link.btn.btn-sm.btn-outline-success.shadow.rounded-pill.mx-1( :to="{name: 'productionPrinter',params:{id: o.cmd_number } }")
                           i.fa.fa-print
                     router-link(:to="{name:'detailOrder',params:{id:o.id}}").mx-2
                        i.fa.fa-list
                     button.btn.btn-outline-danger.rounded-pill(@click="getElementIdToDelete(o.id)" data-target="#bitch" data-toggle="modal")
                        i.fa.fa-trash.my-1
         .col-3.mt-0.segment.tall
            h5.text-primary.text-uppercase Filter Par
            hr
            select-date(@sendDate="getFromDate" title="De" key="DE")
            select-date(@sendDate="getToDate" title="Jusqu'a" key="TO")
            hr
            button.rounded-pill.btn-block.btn-outline-primary.btn.text-uppercase(@click='filterNow')
               | filter
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import TableLayout from "@/components/layouts/TableLayout";
   import SelectDate from "@/components/layouts/SelectDate";
   import DeleteAction from "@/components/Actions/DeleteAction";

   export default {
      name: "OrdersList",
      data() {
         return {
            tableItems: ['#', 'Numéro de livrison', 'date', 'Client', 'Actions'],
            orders: null,
            dateFrom: '',
            dateTo: '',
            deleteURI: ''
         }
      },
      mounted() {
         this.getOrders()
      },
      methods: {
         getOrders() {
            axios.get('/api/orders')
               .then(({data}) => {
                  this.orders = data
                  console.log(data)
               })
               .catch(err => console.log(err.response))
         },
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
         getFromDate(d) {
            this.dateFrom = d
         },
         getToDate(d) {
            this.dateTo = d
         },
         filterNow() {
            axios.get('/api/orders', {
               params: {
                  'from': this.dateFrom,
                  'to': this.dateTo,
               }
            })
               .then(({data}) => {
                  this.orders = data
                  console.log(data)
               })
               .catch(err => console.log(err.response))
         },
         getElementIdToDelete(id) {
            this.deleteURI = `/api/orders/${id}/delete`;
            this.deleteId = id
         },
         refreshOrdersList() {
            this.orders = this.orders.filter(o => o.id !== this.deleteId)
         }
      },
      components: {DeleteAction, SelectDate, TableLayout, BigTitle}
   }
</script>

<style scoped>
   td > button {
      margin-right: 2px;
   }

   .tall {
      height: 320px;
   }
</style>
