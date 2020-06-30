<template>
   <div>
      <div id="molsat" class="row"></div>
      <div class="row" id="offline-molsat">
         <div class="col-12">
            <img src="../../svg/offline.svg" alt="" class="off-img">
         </div>
         <div class="col-12">
            <h1 class="text-center text-capitalize text-primary font-weight-bolder">Vous êtes hors connexion</h1>
            <h3 class="text-center text-capitalize text-primary"> vous devez se connecter pour
               utiliser cette application !</h3>
         </div>
      </div>

   </div>
</template>

<script>
   export default {
      name: "OfflinePage",
   }
</script>

<style scoped>
   #molsat {
      height: 100vh;
      width: 100%;
      top: 0;
      left: 15px;
      position: fixed;
      z-index: 0;
      background: url("/storage/boxes/logo.png") no-repeat;;
      background-size: contain;
      filter: blur(20px);
   }

   #offline-molsat {
      top: 0;
      left: 15px;
      position: fixed;
      z-index: 1;
      height: 100vh;
      width: 100%;
      /*background: rgba(0, 0, 0, 0.9);*/
      /*background: linear-gradient(-135deg, rgba(255, 255, 255, 0.9), rgba(249, 249, 249, 0.91));*/
   }

   .off-img {
      height: 60vh;
      width: 100%;
   }
</style>
