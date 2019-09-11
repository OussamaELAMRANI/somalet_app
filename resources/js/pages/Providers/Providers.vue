<template lang="pug">
   #all-providers
      bread-crumb
      router-view( :key="$route.path")
</template>

<script>
   import BreadCrumb from "@/components/layouts/BreadCrumb";

   export default {
      name: "Providers",
      components: {BreadCrumb}
   }
</script>

<style scoped>
   #all-providers {
      min-height: 100vh;
   }

</style>
