import {ref} from '@vue/composition-api';
import axios from 'axios';

function useApi(defaultUrl, options = {}) {
   const url = ref(defaultUrl);
   const loading = ref(false);
   const result = ref(null);
   const error = ref(null);

   const fetchData = async () => {
      loading.value = true;
      error.value = false;

      return await axios(defaultUrl, options)
         .then(({data}) => {
            result.value = data
            return Promise.resolve(data);
         })
         .catch(err => {
            error.value = err.response;
            return Promise.reject(err.response);

         })
         .finally(() => loading.value = false)
   };


   return {
      url, loading, result, error, fetchData
   }

}

export default useApi;
