<template lang="pug">
   .form-group
      label {{title}}
      datepicker.form-control(:language='fr' :monday-first='true'
         calendar-button-icon='fa fa-user'
         name='date_picker' @selected='getDate')

</template>

<script>
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import moment from 'moment'

   export default {
      name: "SelectDate",
      props:{
         title:{
            default:'Date'
         }
      },
      components: {Datepicker},
      data() {
         return {
            fr,
         }
      },
      methods: {
         getDate(date) {
            this.$emit('sendDate', moment(date).format('YYYY-MM-DD'))
         },
      }
   }
</script>

<style scoped>

</style>
