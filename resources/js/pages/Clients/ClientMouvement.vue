<template>
   <div class="">
      <h5 class="text-uppercase text-secondary">Mouvement Client</h5>
      <hr>
      <div class="row">
         <div class="col-9">
            <div class="row" id="printMe">
               <div class="col-12">
                  <div class="card">
                     <div class="card-header">
                        <h4 class="text-uppercase">Solde Client: <strong class="text-primary"> {{client.nom}}
                           ({{client.id}})</strong></h4>
                     </div>

                     <div class="card-body">
                        <p>
                           Le: <strong>{{dateFrom |humane_date}}</strong>
                           AU: <strong>{{dateTo | humane_date}}</strong>
                        </p>
                        <p class="text-uppercase">Solde de Départ: <strong>{{sold}} DH</strong></p>
                        <p class="text-uppercase">Solde á ce jour: <strong>{{sold_today}} DH</strong></p>
                     </div>
                  </div>
               </div>
               <div class="col-12 my-2">
                  <table class="table table-bordered table-striped text-center">
                     <thead class="bg-primary text-white small">
                     <tr>
                        <th>Date</th>
                        <th>TYPE</th>
                        <th>DEBIT</th>
                        <th>CREDIT</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr v-if="isLoading">
                        <td colspan="6" class="p-0">
                           <div class="row justify-content-center bg-white">
                              <div class="col-2">
                                 <half-circle-spinner color="orange" :size="40"/>
                              </div>
                           </div>
                        </td>
                     </tr>
                     <tr v-if="movements.length==0">
                        <td colspan=4>
                           <h5 class="text-uppercase text-secondary">
                              Pas de résultat !
                           </h5>
                        </td>
                     </tr>

                     <template v-for="(m,i) in movements">

                        <tr v-for="c in m">

                           <template v-if="c.cmd_number || c.state === 'JST'">
                              <td>{{c.date_cmd | humane_date}}</td>
                              <td>
                                 {{(c.state === 'JST') ? 'IMPAYÉ': `COMMANDE: ${c.cmd_number}` }}
                              </td>
                              <td>{{c.amount}} DH</td>
                              <td></td>
                           </template>
                           <template v-else>
                              <td>{{c.payed_at | humane_date}}</td>
                              <td>
                                 <p>
                                    Paiement <strong>({{c.type}})</strong>
                                 </p>
                                 <p>
                                    <strong>{{c.chq_number}}</strong>
                                 </p>
                              </td>
                              <td></td>
                              <td>{{c.amount}} DH</td>
                           </template>
                        </tr>
                     </template>


                     </tbody>
                     <tfoot class="segment">
                     <tr>
                        <td colspan="2">
                           <strong>TOTAL</strong>
                        </td>
                        <td>{{debit}} DH</td>
                        <td>{{credit}} DH</td>
                     </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
            <div class="row">
               <button @click="print"
                       class="btn btn-primary text-uppercase text-white font-weight-bolder float-right btn-block ">
                  Imprimer
               </button>
            </div>
         </div>
         <div class="col-3 mt-0 segment">
            <h5 class="text-primary text-uppercase">Filter Par</h5>
            <hr>
            <div class="form-group">
               <label>DE: </label>
               <datepicker id="date_de" class="form-control rounded-pill" :language='fr' :monday-first='true'
                           v-model="dateFrom"/>
            </div>
            <div class="form-group">
               <label>AU:</label>
               <datepicker id="date_au" class="form-control rounded-pill" :language='fr' :monday-first='true'
                           v-model="dateTo"/>
            </div>
            <hr>
            <button class="rounded-pill btn-block btn-outline-primary btn text-uppercase" @click="filterNow">filter
            </button>
         </div>

      </div>

   </div>

</template>

<script>
   import moment from 'moment';
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import {HalfCircleSpinner} from 'epic-spinners'

   export default {
      components: {Datepicker, HalfCircleSpinner},
      name: "ClientMouvement",
      data() {
         return {
            fr,
            dateFrom: moment(new Date).toDate(),
            dateTo: moment(new Date).toDate(),
            client: {},
            movements: [],
            isLoading: false,
            debits: 0,
            debit: 0,
            credit: 0,
            form_d: moment(new Date).format('YYYY-MM-DD'),
            to_d: moment(new Date).format('YYYY-MM-DD'),
            sold: 0,
            sold_today: 0
         }
      },
      mounted() {
         const id = this.$route.params.id;
         this.isLoading = true;
         this.getMovements(id);
      },
      methods: {
         getMovements(id) {
            axios.get(`/api/clients/${id}/movements`)
               .then(({data}) => {
                  const {movements, client, sold_today, sold} = data;
                  console.log(sold_today)

                  this.client = client;
                  this.movements = movements;
                  this.sold = sold;
                  this.sold_today = sold_today;
                  this.isLoading = false;
               })
               .catch(error => console.log(error.response))
         },
         filterNow() {
            this.isLoading = true;
            const id = this.$route.params.id;
            axios.get(`/api/clients/${id}/movements`, {params: {between: `${this.form_d},${this.to_d}`}})
               .then(({data}) => {
                  console.log(data);
                  const {movements, sold, sold_today} = data;

                  this.movements = movements;
                  this.sold = sold;
                  this.sold_today = sold_today;

                  this.isLoading = false;
               })
               .catch(error => console.log(error.response))
         },
         print() {
            // Pass the element id here
            this.$htmlToPaper('printMe');
         }
      },
      watch: {
         movements: function (v) {

            let td = 0;
            let tc = 0;

            Object.keys(v).forEach(k => {
               this.movements[k].forEach(m => {
                  if (m.date_cmd || m.state === 'JST')
                     td += m.amount;
                  else
                     tc += m.amount;
               })
            });
            this.credit = tc;
            this.debit = td + this.client.old_sold
         },

         dateFrom: function (d) {
            this.form_d = moment(d).format('YYYY-MM-DD');
         },
         dateTo: function (d) {
            this.to_d = moment(d).format('YYYY-MM-DD');
         }

      }
   }

</script>

<style scoped>
   #date_de {
      width: 100px;
   }

   .segment {
      border-top: 2px solid orange;
   }
</style>
