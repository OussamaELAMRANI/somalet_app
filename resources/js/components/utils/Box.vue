<template lang="pug">
   router-link(:to="{name:link} || '#'")
      .segment.box(:class="isActive? 'active-box':''")
         .row.align-items-end
            .col-12
               img(:src="getImage(img)", alt="img" height="60px")
            .col-12.text-center.ar
               h6.mt-2 {{title}}
               p.text-secondary {{subtitle}}
         .row(v-if="hasState")
            .col.text-right
               span.badge.badge-primary.mr-1 0
               span.badge.badge-success 0
</template>

<script>
   export default {
      name: "Box",
      props: ['img', 'title', 'subtitle', 'link', 'isActive','hasState','size'],
      methods: {
         getImage(img) {
            return `/storage/${img}`
            // return `${process.env.MIX_APP_URL}/storage/${img}`
         }
      },
   }
</script>

<style lang="scss" scoped>
   .box {
      height: 160px;
   }

    .box:hover:not(.active-box) {
      cursor: pointer;
      background: #eef4fa;
   }

   .active-box {
      background: rgba(248, 173, 106, 0.51);
      border-bottom: 2px black solid;
   }

</style>
