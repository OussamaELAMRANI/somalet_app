<template lang="pug">
   .row
      .col(v-for=" (t, i) in types" @click="activeBox(t.id)")
         box(:title='t.title' :h='t.h' :isActive="t.isActive" :key="'box_'+i" :img="t.img")

</template>

<script>
   import Box from "@/components/utils/Box";

   export default {
      name: "PaymentType",
      props: {
         types: {}
      },
      methods: {
         activeBox(typeId) {
            _.filter(this.types, (t) => {
               if (t.id === typeId){
                  t.isActive = true
                  this.$emit('getType',t.id)
               }
               else
                  t.isActive = false
            })
         }
      },
      components: {Box}
   }
</script>

<style scoped>

</style>
