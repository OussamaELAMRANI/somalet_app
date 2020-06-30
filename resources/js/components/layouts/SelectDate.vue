<template lang="pug">
   .form-group
      label {{title}}
      datepicker.form-control(:language='fr' :monday-first='true'
         calendar-button-icon='fa fa-user'
         name='date_picker' @selected='getDate' v-model="innerDate")

</template>

<script>
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import moment from 'moment'

   export default {
      name: "SelectDate",
      props: {
         title: {
            default: 'Date'
         },
         currentDate: {
            default: moment.now()
         },
         // Example (fromDate, toDate)[range] ...
         type: {
            default: null
         }
      },
      components: {Datepicker},
      data() {
         return {
            fr,
            innerDate: _.cloneDeep(new Date(this.currentDate))
         }
      },
      methods: {
         getDate(date) {
            this.$emit('sendDate', moment(date).format('YYYY-MM-DD'), this.type)
         },
      },
      mounted() {
         this.getDate()
      },
      watch: {
         currentDate: function (d) {
            if (d)
               this.innerDate = _.cloneDeep(new Date(d))
         }
      }
   }
</script>

<style scoped>

</style>
