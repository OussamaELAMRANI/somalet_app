<template>
   <div id="somalet">
      <router-view/>
   </div>

</template>

<script>
    export default {
        name: "AppRun"
    }
</script>

<style lang="scss">
   input[name='date_picker'] {
      width: 100%;
      border: 0;
   }

   .vdp-datepicker {
      input {
         border: 0 !important;
         width: 100%;
      }
   }
</style>
