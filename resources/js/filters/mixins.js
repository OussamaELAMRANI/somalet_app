import Vue from "vue";
import _ from "lodash";

Vue.mixin({
   methods: {
      isEmpty: inp => _.isEmpty(inp),
      addition: (x, y) => Number(x) + Number(y),
      subtraction: (x, y) => Number(x) - Number(y),
      calcTotal: (qte, price) => Number(qte) * Number(price),
      showUnit: (u, isFirst = true) => {
         const {name} = u;
         const unit = name.split('/')

         if (unit.length > 1 && !isFirst) {
            return unit[1]
         } else if (unit.length > 1 && isFirst) {
            return unit[0]
         } else if (unit.length === 1 && !isFirst) {
            return unit[0]
         } else
            return ''
      }
   }
});
