import './bootstrap'

import Vue from 'vue'
// import moment from 'moment'
import VueHtmlToPaper from 'vue-html-to-paper';
import Popover from 'vue-js-popover'
import VueDump from 'vue-dump';

// import vueRouter from 'vue-router'
import App from './AppRun'

import router from '@/routes'
import store from './store'
import '@/permission'; // permission control
import '@/validations'; // Validation Rules

// import VeeValidate from 'vee-validate'
// import VeeValidate, {Validator} from 'vee-validate';

import VueNotification from "@kugatsu/vuenotification";
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import VueSelect from 'vue-cool-select'
import VueDialog from 'vue2-dialog'
import 'vue2-dialog/dist/VueDialog.css'
import ToggleSwitch from 'vuejs-toggle-switch'
// import on your project (less then 1KB gziped)
import vueSmoothScroll from 'vue-scrollto'
import VueCompositionAPI from '@vue/composition-api'
import VueOffline from 'vue-offline'


Vue.component('pagination', require('laravel-vue-pagination'));


/**
 * Add Plugins
 */
import Multiselect from 'vue-multiselect'

// Add Validation Inputs
// import fr from 'vee-validate/dist/locale/fr';

// Vue.use(Breabcrumbs);

// Vue.use(VeeValidate);
// Validator.localize('fr', fr);

Vue.use(VueNotification, {
   error: {
      background: "white",
      color: "red"
   },
   showCloseIcn: true,
   timer: 20
});
Vue.use(VueFormWizard);
Vue.use(VueDialog)
Vue.use(ToggleSwitch)

Vue.use(VueSelect, {
   theme: 'bootstrap' // or 'material-design'
})
Vue.use(vueSmoothScroll, {
   container: "body",
   duration: 500,
   easing: "ease",
   offset: 0,
   force: true,
   cancelable: true,
   onStart: false,
   onDone: false,
   onCancel: false,
   x: false,
   y: true
})

Vue.component('multiselect', Multiselect);

const printerOptions = {
   name: '_blank',
   specs: [
      'fullscreen=yes',
      'titlebar=yes',
      'scrollbars=yes'
   ],
   styles: [
      'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
      'https://unpkg.com/kidlat-css/css/kidlat.css'
   ]
}

Vue.use(VueHtmlToPaper, printerOptions);
Vue.use(Popover);
import VPopover from 'vue-js-popover'

Vue.use(VPopover, {tooltip: true})

import '@/filters/directives'
import '@/filters/mixins'

Vue.use(VueCompositionAPI);
Vue.use(VueOffline);
Vue.use(VueDump);

new Vue(Vue.util.extend({router, store}, App)).$mount('#app');
