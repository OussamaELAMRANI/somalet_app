<template lang="pug">
   #stock
      big-title(title="STOCK", h='h1')
      router-view
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   export default {
      name: "Stocks",
      components: {BigTitle}
   }
</script>

<style scoped>

</style>
