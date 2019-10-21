<template lang="pug">
   nav(aria-label='breadcrumb')
      ol.breadcrumb
         li.breadcrumb-item(v-for=" (r,i) in routes" :class="((routes.length-1)===i)? 'active' : ''").text-capitalize
            template(v-if="((routes.length-1)!==i)")
               i(:class="r.meta.icon + ' text-primary'")
               router-link(:to="{name:r.name}")  {{r.meta.title}}
            template(v-else) {{r.meta.title}}

</template>

<script>
   export default {
      name: "BreadCrumb",
      data() {
         return {
            routes: []
         }
      },
      mounted() {
         // _.forEach(this.$route, routes => {
         // console.log(this.$route.matched)
         this.routes = this.$route.matched
         // })
      },
   }
</script>

<style lang="scss" scoped>
   $orange: #ff9f42;

   .breadcrumb {
      background: transparent;
      margin-bottom: 20px;
      border-bottom: 2px solid $orange;
      border-radius: 0;
      width: 60%;
   }
   i{
      color: darken($orange,30);
      margin-right: 2px;
   }
</style>
