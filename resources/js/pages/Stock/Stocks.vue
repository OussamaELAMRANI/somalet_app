   <template lang="pug">
   #stock
      bread-crumb
      big-title(title="STOCK", :classes="['text-uppercase','h2']")
      router-view
</template>

<script>
   import BigTitle from "@/components/layouts/BigTitle";
   import BreadCrumb from "@/components/layouts/BreadCrumb";
   export default {
      name: "Stocks",
      components: {BreadCrumb, BigTitle}
   }
</script>

<style scoped>

</style>
