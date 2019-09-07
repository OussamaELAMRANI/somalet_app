<template lang="pug">
   #orders
      big-title(title="Commandes" :classes="['text-uppercase','h2']")
      router-view
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "Order",
      components: {BigTitle}
   }
</script>

<style scoped>

</style>
