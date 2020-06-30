<template lang="pug">
   #payments
      bread-crumb
      router-view
</template>

<script>
   import BreadCrumb from "@/components/layouts/BreadCrumb";

   export default {
      name: "Payments",
      components: {BreadCrumb}
   }
</script>

<style scoped>

</style>
