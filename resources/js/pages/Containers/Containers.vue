<template>
   <div id="containers" class="container">
      <bread-crumb/>
      <router-view/>
   </div>
</template>

<script>
   import BreadCrumb from "@/components/layouts/BreadCrumb";

   export default {
      name: "Containers",
      components: {BreadCrumb}
   }
</script>

<style scoped>

</style>
