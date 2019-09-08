<template lang="pug">
   #orders
      bread-crumb
      big-title(title="Commandes" :classes="['text-uppercase','h2']")
      router-view
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import BreadCrumb from "@/components/layouts/BreadCrumb";

   export default {
      name: "Order",
      components: {BreadCrumb, BigTitle}
   }
</script>

<style scoped>

</style>
