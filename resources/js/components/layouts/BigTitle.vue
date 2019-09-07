<template lang="pug">
   .big-header
      h4.my-3(:class="[position, h, classes]") {{title}}
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
         h: {},
         classes: {
            default: function () {
               return ['text-success']
            }
         },

      }
   }
</script>

<style scoped>

</style>
