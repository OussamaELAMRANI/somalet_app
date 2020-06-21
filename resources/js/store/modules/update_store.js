const state = {
   url: '',
   data: '',
   redirect: null,
};


const mutations = {
   SET_URL: (state, url) => {
      state.url = url
   },
   SET_DATA: (state, data) => {
      state.data = data
   },
   SET_REDIRECT: (state, redirect) => {
      state.redirect = redirect
   }
};

const actions = {

   getData({commit, getters, state}, {url, params, router}) {

      if (url === state.url && state.redirect) {
         router.push({name: 'notFound'});
         return;
      }

      commit('SET_URL', url);
      return axios.get(url, params)
         .then(({data}) => {
            commit('SET_DATA', data);
            commit('SET_REDIRECT', false);
            return Promise.resolve(data)

         })
         .catch((error) => {
            commit('SET_REDIRECT', true);
            return Promise.reject(error.response)
         });
   }
};

export default {
   namespaced: true,
   state,
   mutations,
   actions,
};
