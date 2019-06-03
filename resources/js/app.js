import './bootstrap'

import Vue from 'vue'

import vueRouter from 'vue-router'
import App from './AppRun'

import router from '@/routes'
import store from './store'
import '@/permission'; // permission control

// import VeeValidate from 'vee-validate'
import VeeValidate, {Validator} from 'vee-validate';
import VueNotification from "@kugatsu/vuenotification";
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import VueSelect from 'vue-cool-select'
import VueDialog from 'vue2-dialog'
import 'vue2-dialog/dist/VueDialog.css'
import ToggleSwitch from 'vuejs-toggle-switch'
/**
 * Add Plugins
 */

// Add Validation Inputs
import fr from 'vee-validate/dist/locale/fr';

Vue.use(VeeValidate);
Validator.localize('fr', fr);

Vue.use(VueNotification, {
    timer: 20
});
Vue.use(VueFormWizard);
Vue.use(VueDialog)
Vue.use(ToggleSwitch)

Vue.use(VueSelect, {
    theme: 'bootstrap' // or 'material-design'
})

new Vue(Vue.util.extend({router, store}, App)).$mount('#app');
