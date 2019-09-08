<template lang="pug">
   #biling_page.my-4
      //h2 Facturation
      bread-crumb
      router-view
</template>

<script>
   import BreadCrumb from "@/components/layouts/BreadCrumb";
   export default {
      name: "Billings",
      components: {BreadCrumb}
   }
</script>

<style scoped>

</style>
