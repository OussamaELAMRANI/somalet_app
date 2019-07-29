<template lang="pug">
   .row
      .col(v-for=" (t, i) in types" @click="activeBox(t.id)")
         box(:title='t.title' :isActive="t.isActive" :key="'box_'+i" :img="t.img")

</template>

<script>
   import Box from "@/components/utils/Box";

   export default {
      name: "ProductType",
      components: {Box},
      props: {
         types: {}
      },
      methods: {
         activeBox(typeId) {
            console.log('cc')
            _.filter(this.types, (t) => {
               if (t.id === typeId){
                  t.isActive = true
                  this.$emit('getType',t.id)
               }
               else
                  t.isActive = false
            })
         }
      }
   }
</script>

<style scoped>

</style>
