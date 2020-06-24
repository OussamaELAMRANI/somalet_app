import Vue from "vue";
import _ from "lodash";

Vue.mixin({
   methods: {
      isEmpty: inp => _.isEmpty(inp),
      addition: (x, y) => Number(x) + Number(y),
      subtraction: (x, y) => Number(x) - Number(y),
      total: (qte, price) => Number(qte) * Number(price)
   }
});
