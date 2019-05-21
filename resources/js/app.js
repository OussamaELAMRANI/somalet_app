import './bootstrap'

import Vue from 'vue'

import vueRouter from 'vue-router'
import App from './AppRun'
import Routes from './routes'
import store from './store'
// import VeeValidate from 'vee-validate'
import VeeValidate, { Validator } from 'vee-validate';
import VueNotification from "@kugatsu/vuenotification";
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

import VueSelect from 'vue-cool-select'


/**
 * Add Plugins
 */
// Vue Routes
Vue.use(vueRouter);
// Vuex or the State Manager

// Add Validation Inputs
import fr from 'vee-validate/dist/locale/fr';
Vue.use(VeeValidate);
Validator.localize('fr', fr);

Vue.use(VueNotification, {
    timer: 20
});
Vue.use(VueFormWizard)

Vue.use(VueSelect, {
    theme: 'bootstrap' // or 'material-design'
})

const router = new vueRouter({
    routes: Routes,
    scrollBehavior(to, from, savedPosition) {
        return {x: 0, y: 0}
    },
    mode: 'history'
});
//
router.beforeEach((to, from, next) => {

        // Test Authentification
        if (to.matched.some(record => record.meta.requiresAuth)) {
            store.dispatch('isAuthenticated').then(isAuth => {

                if (!isAuth)
                    next({path: '/login', query: {redirect: to.fullPath}});
                else
                    next()
            })
        }
        if (to.matched.some(record => record.meta.forVisitor)) {
            store.dispatch('isAuthenticated').then(isAuth => {
                if (isAuth)
                    next({path: '/dashboard'})
                else
                    next()
            })

        } else
            next()


    }
);


new Vue(Vue.util.extend({router, store}, App)).$mount('#app');
