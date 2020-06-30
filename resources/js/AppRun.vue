<template>
   <div id="somalet">
      <offline-page v-if="isOffline"/>
      <router-view v-if="isOnline"/>
   </div>

</template>

<script>
   import OfflinePage from "@/components/layouts/OfflinePage";

   export default {
      name: "AppRun",
      components: {OfflinePage}
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
