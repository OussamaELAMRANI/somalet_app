import {reactive, toRefs, onMounted} from '@vue/composition-api';
import useApi from "@/hooks/api/PromiseApi";

function useBaseBank(type = 'external') {
   const state = reactive({
      banks: null
   });

   const getTypes = useApi(`/api/banks/${type}`);

   onMounted(async () => {
      await getTypes.fetchData();
      state.banks = await getTypes.result
   });

   return {
      ...toRefs(state)
   }
}

export function useBanks() {
   return useBaseBank()
}

export function useInternalBanks() {
   return useBaseBank('internals')
}

export function useManageBank(url, data, method = 'POST', params = []) {

   const post = useApi({
      url,
      method,
      data,
      params
   });

   const sendData = () => post.fetchData();

   return {
      sendData
   }
}
