<template lang="pug">
   router-link(:to="link || '#'")
      .segment.box(:class="isActive? 'active-box':''")
         .row.align-items-start
            .col-8
               img(:src="getImage(img)", alt="img" height="60px")
            .col-4(v-if="hasState")
               slot

            .col-12.text-center.ar
               h6.mt-2(:class="h") {{title}}
               p.text-secondary {{subtitle}}

</template>

<script>
export default {
   name: "Box",
   props: ['img', 'title', 'subtitle', 'link', 'isActive', 'hasState', 'size', 'h'],
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
