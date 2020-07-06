<template>
   <div class="new-payments">
      <loading-page v-if="isLoading && updateCase"/>
      <template>
         <big-title :title="(updateCase?'Modifier ':'Nouveau ') + 'Paiement'" position="text-left"
                    classes="text-secondary my-4"/>
         <div class="row justify-content-center">
            <form class="segment col-10" @submit.prevent="">
               <ValidationObserver v-slot="{ handleSubmit }">
                  <div class="form-group">
                     <div class="row justify-content-center my-3">
                        <div class="col-4" v-if="updateCase">
                           <div class="form-group">
                              <p>Client:</p>
                              <p class="lead text-primary">{{client | getByChoice('nom')}}</p>
                           </div>
                        </div>
                        <div class="col-4" v-else>
                           <select-client @sendClient="$getClient"/>
                           <small v-if="!client_id" class="text-danger m-2"> {{ err.client }} </small>
                        </div>
                        <div class="col-4">
                           <div class="form-group">
                              <label for="payment-type">Mode de paiément</label>
                              <ValidationProvider name="types" rules="required" v-slot="{ errors }">
                                 <select class="form-control" id="payment-type" v-if="types" v-model="typed">
                                    <option :value="null" disabled="disabled">Selectionnez ici</option>
                                    <option v-for="(t,i) in types" :value="t.id">{{t.type}}</option>
                                 </select>
                                 <small-loading v-else/>
                                 <small class="text-danger m-2">{{ errors[0] }}</small>
                              </ValidationProvider>
                           </div>
                        </div>
                     </div>
                     <div class="row justify-content-center">
                        <div class="col-12">
                           <hr/>
                        </div>
                        <div class="col-6">
                           <select-date title="Date d'encaissement" @sendDate="$getDate" :type="true"
                                        :currentDate="payed_at"/>
                        </div>
                        <div class="col-6">
                           <label>Montant</label>
                           <ValidationProvider name="Montant" rules="required|min:1" v-slot="{ errors }">
                              <div class="input-group">
                                 <input class="form-control" type="number" v-model.number="amount"/>
                                 <div class="input-group-append"><span class="input-group-text">DH</span></div>
                              </div>
                              <small class="text-danger m-2">{{ errors[0] }}</small>
                           </ValidationProvider>
                        </div>
                        <!--                     <template v-if="['CHEQUE','EFFET'].includes(pType)">-->
                        <div class="col-4">
                           <div class="form-group">
                              <label for="from-bank">Banque</label>
                              <select id="from-bank" class="form-control" v-model="from_bank" v-if="banks">
                                 <option :value="null">Indifférent</option>
                                 <option v-for="(t,i) in banks" :value="t.id">{{t.company}}</option>
                              </select>
                              <small-loading v-else/>
                           </div>
                        </div>
                        <div class="col-4">
                           <select-date title="Date d'échéance" @sendDate="$getDate" :type="false"
                                        :currentDate="date_deadline"/>
                        </div>
                        <div class="col-4">
                           <div class="form-group">
                              <label for="num">Numéro</label>
                              <input id="num" type="text" class="form-control" placeholder="Numero de CHQ/EFF"
                                     v-model="chq_number">
                           </div>
                        </div>
                        <!--                     </template>-->
                        <div class="col-12">
                           <div class="form-group">
                              <label for="designation">Designation</label>
                              <textarea id="designation" class="form-control" placeholder="Designation ..."
                                        v-model="designation"/>
                           </div>
                        </div>
                     </div>
                     <hr/>
                     <div class="row justify-content-end">
                        <div class="col-3 text-right">
                           <button class="btn btn-primary" v-if="types" @click="handleSubmit(checkout)">
                              Enregistrer ce paiement
                           </button>
                        </div>
                     </div>
                  </div>
               </ValidationObserver>
            </form>
         </div>
      </template>
   </div>
</template>

<script>
   import {ref, reactive, onMounted, computed, toRefs, watch,} from '@vue/composition-api';
   import useApi from "@/hooks/api/PromiseApi";
   import LoadingPage from "@/components/layouts/LoadingPage";
   import BigTitle from "@/components/layouts/BigTitle";
   import SmallLoading from "@/components/layouts/SmallLoading";
   import {usePaymentType, managePayment} from '@/Compositions/use-payments'
   import {useBanks} from '@/Compositions/use-banks'
   import SelectDate from "@/components/layouts/SelectDate";
   import SelectClient from "@/components/Client/SelectClient";
   import {ValidationObserver} from 'vee-validate';

   export default {
      name: 'newPayment',
      components: {SelectClient, SelectDate, SmallLoading, BigTitle, LoadingPage, ValidationObserver},
      setup(props, context) {
         const state = reactive({
            payed_at: null,
            date_deadline: null,
            typed: null,
            from_bank: null,
            amount: 0,
            chq_number: null,
            designation: null,
            // pType: null,
            client_id: null,
         });
         const err = ref({});
         const updateCase = ref(null);
         const client = ref(null);
         const isLoading = ref(true);

         const types = usePaymentType();

         onMounted(() => {
            const {id} = context.root.$route.params;
            if (id) {
               updateCase.value = id;
               useApi(`/api/payments/${id}`).fetchData()
                  .then(data => {
                     Object.keys(state).forEach(k => {
                        state[k] = data[k];
                     });
                     client.value = data.client
                     isLoading.value = false;
                  })
            } else isLoading.value = false;

         });

         const $getClient = (client) => state.client_id = client;

         const $getDate = (d, t) => (t) ? state.payed_at = d : state.date_deadline = d;

         const checkout = () => {
            let url = '/api/payments/new';
            let method = 'POST';
            let data = {payment: state};
            let message = 'Paiément a été bien ';

            if (updateCase.value) {
               url = `/api/payments/${updateCase.value}/update`;
               method = 'PUT';
               message += 'modifier';

            } else {
               if (state.client_id === null) {
                  err.value = {client: "Vous devez séléctionner un client !"}
                  return
               }
               message += 'ajouté';
            }
            managePayment(data, url, method)
               .sendPayment()
               .then(data => {
                  context.root.$notification.success(message);
                  context.root.$router.push({name: 'paymentList'})
               })
               .catch(err => {
                  this.$notification.error(err);
                  console.log(err)
               })
         };

         return {
            ...types,
            err, updateCase, client, isLoading,
            ...useBanks(),
            ...toRefs(state),
            $getClient, $getDate, checkout,
         }
      }
   };

</script>
