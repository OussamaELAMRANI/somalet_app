<template lang="pug">
   #Products
      bread-crumb
      big-title(title="Gestion des Produits" position="text-left")
      router-view(:key="$route.path")
</template>

<script>
   import StepBox from "../../components/Steps/StepBox";
   import BigTitle from "@/components/layouts/BigTitle";
   import BreadCrumb from "@/components/layouts/BreadCrumb";

   export default {
      name: "Products",
      methods: {
         goTo(nameRoute) {
            this.$router.push({name: nameRoute})
         }
      },
      computed: {
         getUrl() {
            return this.$route.name
         }
      },
      components: {BreadCrumb, BigTitle, StepBox}
   }
</script>

<style scoped>
   #Products {
      min-height: 100vh;
   }
</style>
