<template lang="pug">
   #Bank
      h3.text-secondary.text-uppercase.text-center.my-3  Banques
      hr
      .row.justify-content-center
         .btn-group
            router-link(:to="{name:'bank_if'}").btn.btn-primary IF Banque
            router-link(:to="{name:'bank_molsat'}").btn.btn-success MOLSAT
            router-link(:to="{name:'bank_bb'}").btn.btn-secondary BB
      router-view(:key="$route.path")
</template>

<script>
    export default {
        name: "Banks"
    }
</script>

<style scoped>

</style>
