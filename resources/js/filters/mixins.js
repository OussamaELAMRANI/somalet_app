import Vue from "vue";
import _ from "lodash";

Vue.mixin({
   methods: {
      isEmpty: inp => _.isEmpty(inp),
   }
});
