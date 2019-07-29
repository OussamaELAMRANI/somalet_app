<template lang="pug">
   .big-header
      h4.text-success.my-3(:class="position") {{title}}
         span.text-secondary {{subtitle}}
      .dropdown-divider(v-if="separator")
</template>

<script>
   export default {
      name: "BigTitle",
      props: {
         title: {},
         subtitle: {},
         separator: {
            default: true
         },
         position: {
            default: 'text-center'
         },

      }
   }
</script>

<style scoped>

</style>
