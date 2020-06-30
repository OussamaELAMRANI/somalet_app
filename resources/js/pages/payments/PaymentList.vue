<template>
   <div class="payments">
      <delete-action :url="deleteURI" @isDeleted="refreshOrdersList"/>

      <loading-page v-if="loading"/>
      <div class="row" v-else>
         <div class="col-9">
            <table-layout
               :headTable="['#','Date','Type',`Date d'échéance`,'Client','Montant' , 'Actions']"
               :data="result">
               <tr v-for="(p,i) in result">
                  <td>
                     <p class="text-success" v-if="p.valid">
                        <i class="fa fa-check"/>
                     </p>
                     <p class="text-warning" v-else>
                        <i class="fa fa-minus"/>
                     </p>
                  </td>
                  <td>{{p.payed_at | humane_date}}</td>
                  <td>
                     <span class="badge badge-success">
                        {{p.types | getByChoice('type')}}
                     </span>
                  </td>
                  <td>{{ (['EFFET','CHEQUE'].includes(p.types.type)) ? p.date_deadline: null}}</td>
                  <td>
                     <router-link v-if="p.client" :to="{name:'movementClient',params:{id:p.client.id}}">
                        {{p.client | getByChoice('nom')}}
                     </router-link>
                  </td>
                  <td>{{p.amount}} DH</td>
                  <td>
                     <div class="btn-group">
                        <button class="btn btn-outline-danger btn-sm" @click="getElementIdToDelete(p.id)"
                                data-target="#bitch" data-toggle="modal" v-if="!p.valid">
                           <i class="fa fa-trash"/>
                        </button>
                        <router-link :to="{name:'paymentUpdate', params:{id:p.id}}" class="btn btn-outline-info btn-sm"
                                     v-if="roles.includes('ADMINE')">
                           <i class="fa fa-wrench"/>
                        </router-link>
<!--                        <button class="btn btn-outline-primary btn-sm">-->
                        <!--                           <i class="fa fa-list"/>-->
                        <!--                        </button>-->
                     </div>
                  </td>
               </tr>
            </table-layout>
         </div>
         <div class="col-3 segment tall">
            <h5 class="text-primary text-uppercase">Filter Par</h5>
            <hr>
            <div class="custom-control custom-switch">
               <input type="checkbox" class="custom-control-input" id="customSwitch1" v-model="valid"/>
               <label for="customSwitch1" class="custom-control-label">
                  Annulation {{valid ? null : 'no ' }} Valide
               </label>
            </div>

            <div class="form-group">
               <label>Nom de Client</label>
               <input type="text" class="form-control" placeholder="Nom et prénom ..." v-model="client">
            </div>

            <hr>
            <select-date @sendDate="getDate" :type="true" title="De" key="DE"/>
            <select-date @sendDate="getDate" :type="false" title="Jusqu'a" key="TO"/>
            <hr>
            <button class="rounded-pill btn-block btn-outline-primary btn text-uppercase" @click="search">
               filter
            </button>
         </div>
      </div>
   </div>
</template>

<script>
   import {ref, reactive, onMounted, computed, toRefs, watch} from '@vue/composition-api';
   import useApi from "@/hooks/api/PromiseApi";
   import LoadingPage from "@/components/layouts/LoadingPage";
   import TableLayout from "@/components/layouts/TableLayout";
   import SelectDate from "@/components/layouts/SelectDate";
   import DeleteAction from "@/components/Actions/DeleteAction";
   import {useSearch} from '@/Compositions/use-payments'
   import store from "@/store";

   export default {
      components: {DeleteAction, SelectDate, TableLayout, LoadingPage},
      setup() {
         const deleteURI = ref(null);
         const deleteId = ref(null);
         const roles = ref(store.getters.roles)

         const getPayments = useSearch();
         const getElementIdToDelete = (id) => {
            deleteURI.value = `/api/payments/${id}/delete`;
            deleteId.value = id
         };

         const refreshOrdersList = () => {
            const {result} = getPayments;
            result.value = result.value.filter(o => o.id !== deleteId.value)
         };

         return {
            ...getPayments, deleteURI, deleteId, roles,
            getElementIdToDelete, refreshOrdersList
         }
      },
   };


</script>
