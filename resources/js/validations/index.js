import Vue from "vue";
import {ValidationProvider, extend} from 'vee-validate';

// REQUIRED RULE.
extend('required', {
   validate(value) {
      return {
         required: true,
         valid: ['', null, undefined].indexOf(value) === -1
      };
   },
   computesRequired: true,
   message: 'Vous devez saisire votre {_field_}'
});


// VALIDATION RULES
Vue.component('ValidationProvider', ValidationProvider);

