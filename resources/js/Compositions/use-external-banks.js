import {reactive, toRefs, onMounted} from '@vue/composition-api';
import useApi from "@/hooks/api/PromiseApi";

export function useExternalBanks() {
   const state = reactive({
      banks: null
   });

   const getTypes = useApi('/api/banks/external');

   onMounted(async () => {
      await getTypes.fetchData();
      state.banks = await getTypes.result
   });

   return {
      ...toRefs(state)
   }
}

