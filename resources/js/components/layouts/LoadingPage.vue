<template lang="pug">
   div#lood
      #molsat.row
      #loading_page.row.justify-content-center.align-items-center
         .col-2.text-center
            half-circle-spinner(color="#FF952F", :size="100")
</template>

<script>
   import HalfCircleSpinner from "epic-spinners/src/components/lib/HalfCircleSpinner";

   export default {
      name: "LoadingPage",
      components: {HalfCircleSpinner}
   }
</script>

<style scoped>
   #lood {
      top: 0;
      left: 0;
      position: fixed;
      background: #fff;
      height: 100vh;
      width: 100%;
      z-index: 1;
   }

   #molsat {
      height: 100vh;
      width: 100%;
      top: 0;
      left: 15px;
      position: fixed;
      z-index: 0;
      background: url("/storage/boxes/logo.png") no-repeat;
      background-size: cover;
      filter: blur(14px);
   }

   #loading_page {
      position: fixed;
      top: 0;
      left: 15px;
      height: 100vh;
      width: 100%;
      z-index: 100;
      background: rgba(255, 255, 255, 0.78);
      /*display: flex;*/
      /*justify-content: center;*/
      /*align-items: center;*/
      /*height: 90vh;*/
   }

   #loading_core {
      max-width: 200px;
      text-align: center;
      background: rgba(255, 255, 255, 0.5);
      margin: .25rem;
      padding: .25rem;
      align-self: center;
   }

</style>
