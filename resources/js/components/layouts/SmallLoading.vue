<template lang="pug">
   div.justify-content-center.d-flex
      half-circle-spinner(color="#FF952F", :size="40")
</template>

<script>
   import HalfCircleSpinner from "epic-spinners/src/components/lib/HalfCircleSpinner";

   export default {
      name: "SmallLoading",
      components: {HalfCircleSpinner}
   }
</script>

<style scoped>

</style>
