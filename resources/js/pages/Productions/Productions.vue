<template lang="pug">
   #productions
      bread-crumb
      router-view
</template>

<script>
    import BreadCrumb from "@/components/layouts/BreadCrumb";
    export default {
        name: "Productions",
       components: {BreadCrumb}
    }
</script>

<style scoped>

</style>
