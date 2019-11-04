<template lang="pug">
   router-link(:to="go")
      .segment.shadow.text-center
         .header
            h3.text-uppercase.text-secondary {{month}}
         .footer
            p.text-primary.lead.my-2 {{year}}
</template>

<script>
    export default {
        name: "WalletMonth",
        props: {
            month: {},
            year: {},
            go: {},
        },
    }
</script>

<style scoped>
   .segment {
      text-decoration: none;

   }

   .segment:hover {
      border-bottom: 2px orange solid;
      cursor: pointer;
   }

   .segment:active {
      /*border-bottom: 2px indigo solid;*/
      cursor: pointer;
      background-color: #ffdf58;
   }
</style>
