<template>
   <div id="transfer-to-bank">
      <big-title title="Transfert Banque au Banque"/>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-12">
               <table-layout
                  :headTable="['#','Date',`Date d'échéance`,'Numéro','Client', 'Montant','Type', 'Operation','Banque','designation']"
                  :data="payment">
                  <tr v-if="payment">
                     <td>{{payment.id}}</td>
                     <td>{{payment.payed_at | humane_date}}</td>
                     <td>{{payment.date_deadline | humane_date}}</td>
                     <td>{{payment.chq_number}}</td>
                     <td class="font-weight-bolder">{{payment.client | getByChoice('nom')}}</td>
                     <td>{{payment.amount}} DH</td>
                     <td><p class="badge badge-success">{{payment.types | getByChoice('type')}}</p></td>
                     <td><p class="badge-info">{{payment.operation | paymentOperation }}</p></td>
                     <td>{{payment.form_bank | getBank}}</td>
                     <td>{{payment.designation}}</td>
                  </tr>
               </table-layout>
            </div>
            <div class="col-9 segment">
               <form @submit.prevent="">
                  <div class="form-group row justify-content-center align-items-end">
                     <div class="col-6" v-if="loading">
                        <small-loading/>
                     </div>
                     <div class="col-6" v-if="payment">
                        <label for="to-bank">Transférer dans la banque:</label>
                        <select name="" id="to-bank" class="form-control" v-model="payment.to_bank.id"
                                @change="getBankId">
                           <option value="" v-for="b in banks" :value="b.id">{{b.name}}</option>
                        </select>
                     </div>
                     <div class="col-4 text-right">
                        <button class="btn btn-primary" @click="transfer">Transférer</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
<!--      <dump :data="result" label="The payment 👨‍🎨"/>-->
   </div>
</template>

<script>
   import {reactive, toRefs, onMounted} from '@vue/composition-api'
   import useApi from "@/hooks/api/PromiseApi";
   import BigTitle from "@/components/layouts/BigTitle";
   import {useInternalBanks, useManageBank} from "@/Compositions/use-banks";
   import SmallLoading from "@/components/layouts/SmallLoading";
   import TableLayout from "@/components/layouts/TableLayout";

   export default {
      components: {TableLayout, SmallLoading, BigTitle},
      setup(props, context) {
         const state = reactive({
            payment: null,
            bank_id: null
         });
         const {id} = context.root.$route.params;
         const getPayment = useApi(`/api/payments/${id}`);

         onMounted(() => {
            getPayment.fetchData().then(
               result => state.payment = result
            );
         });
         const getBankId = (e) => {
            state.bank_id = e.target.value
         };

         const transfer = () => useManageBank(
            `/api/payments/transfer/${id}/bank`,
            {in_bank: state.bank_id},
            'PUT'
         ).sendData().then(data => {
            context.root.$notification.success('Transfert a été bien fait ');
            context.root.$router.back();
         });

         return {
            ...toRefs(state),
            ...getPayment,
            ...useInternalBanks(),
            getBankId,
            transfer
         }
      }
   }
</script>

<style scoped>

</style>
