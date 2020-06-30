import {reactive, toRefs, onMounted, watch} from '@vue/composition-api';
import useApi from "@/hooks/api/PromiseApi";

export function usePaymentType() {
   const state = reactive({
      types: null
   });

   const getTypes = useApi('/api/payments-type');

   onMounted(() => {
      getTypes.fetchData();
      state.types = getTypes.result
   });

   return {
      ...toRefs(state)
   }
}

export function managePayment(data, url = '', method = 'POST') {

   const post = useApi({
      url,
      method,
      data
   });

   const sendPayment = () => post.fetchData();

   return {
      sendPayment
   }
}

export function useSearch() {
   const state = reactive({
      valid: null,
      client: null,
      fromDate: null,
      toDate: null,
   });


   const request = {
      url: `/api/payments/search`,
      method: 'GET',
      params: {
         with: 'client,types',
         take: 50
      }
   };

   watch((state), (s, prevState) => {
      request['params'] = {
         with: 'client,types',
         take: 100,
         valid: s.valid,
         of: s.client,
         between: [s.fromDate, s.toDate].join()
      }
   });

   const getDate = (d, type) => (type) ? state.fromDate = d : state.toDate = d;

   const getResult = useApi(request);

   const search = () => getResult.fetchData();

   onMounted(() => {
      search();
   });

   return {
      ...getResult,
      ...toRefs(state),
      getDate,
      search
   }
}
