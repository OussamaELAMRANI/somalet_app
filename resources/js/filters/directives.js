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

Vue.filter('full_date', function (date) {
   if (date !== null)
      return moment(date).format('DD-MM-YYYY HH:mm');
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

Vue.filter('getProductName', function (p) {
   return (p == null) ? null : p.name
});

Vue.filter('getProductSize', function (p) {
   return (p == null) ? null : p.size
});

Vue.filter('getQteFabric', function (p) {
   return (p == null) ? null : p.fabric_quantity
});

Vue.filter('getOrderQte', function (p) {
   return (p == null) ? null : p.order_quantity
});

Vue.filter('getByChoice', function (p, choice) {
   return (p == null) ? null : p[choice]
});

Vue.filter('paymentOperation', function (o) {
   let operation = null;
   switch (o) {
      case 'PYM':
         operation = 'Paiement';
         break;
      case 'TRS':
         operation = 'Transfert';
         break;
      default:
         operation = 'Charges';
   }

   return (o == null) ? null : operation;
});
