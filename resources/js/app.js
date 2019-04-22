import './bootstrap'

import Vue from 'vue'

import vueRouter from 'vue-router'
import App from './AppRun'
import Routes from './routes'
import store from './store'

/**
 * Add Plugins
 */
// Vue Routes
Vue.use(vueRouter);
// Vuex or the State Manager

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
                    next({path: '/admin'})
                else
                    next()
            })

        }
        else
            next()


    }
);


new Vue(Vue.util.extend({router, store}, App)).$mount('#app');
