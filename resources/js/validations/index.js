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
   message: 'Vous devez saisire {_field_}'
});

extend('max', {
   validate(value, args) {
      return value <= args.max;
   },
   params: ['max'],
   message: `Max est {max}`
});

extend('min', {
   validate(value, args) {
      return value >= args.min;
   },
   params: ['min'],
   message: `Min est {min}`
});


// VALIDATION RULES
Vue.component('ValidationProvider', ValidationProvider);

