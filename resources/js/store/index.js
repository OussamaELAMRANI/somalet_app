import Vue from 'vue'
import Vuex from 'vuex'

// used to save Data in the Storage
import VuexPersist from 'vuex-persist';

import users from './modules/userStore'

//Plugin
Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const vuexLocalStorage = new VuexPersist({
    key: 'vuex', // The key to store the state on in the storage provider.
    storage: window.localStorage, // or window.sessionStorage or localForage
    // Function that passes the state and returns the state with only the objects you want to store.
    reducer: state => state,
    // Function that passes a mutation and lets you decide if it should update the state in localStorage.
    // filter: mutation => (true)
})

export default new Vuex.Store({
    modules: {
        users
    },
    strict: debug,

    plugins: [vuexLocalStorage.plugin]
})
