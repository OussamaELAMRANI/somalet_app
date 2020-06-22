const state = {
   providers: []
};

const mutations = {
   SET_PROVIDERS: (state, providers) => {
      state.providers = providers
   },
};

const actions = {
   getProviders({commit, state}) {

      if (state.providers.length > 0) {
         return
      }

      return axios.get('/api/providers')
         .then(({data}) => {
            commit('SET_PROVIDERS', data)
            return Promise.resolve(data)
         })
         .catch(error => console.log(error.response))
   }
};

export default {
   namespaced: true,
   state,
   mutations,
   actions,
};
