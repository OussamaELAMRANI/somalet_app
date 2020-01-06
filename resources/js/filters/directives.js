import Vue from "vue";
import moment from "moment";

/**
 * Global Filtering | pipeline truck
 */
Vue.filter('humane_date', function (date) {
   if (date !== null)
      return moment(date).format('DD-MM-YYYY');
   return null
})
Vue.filter('fixed_two', function (n) {
   if (n !== null)
      return Number(n).toFixed(2);
   return null
});
Vue.filter('clientName', function (c) {
   if (c !== null)
      return c.nom;
   return null
});

Vue.filter('paymentMode', function (c) {
   if (c !== null)
      return c.type;
   return null
});
Vue.filter('getBank', function (c) {
   if (c !== null)
      return c.name;
   return null
});
