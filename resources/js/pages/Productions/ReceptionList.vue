<template lang="pug">
   #reception-list
      h4 RECEPTION LISTE
      hr
      .row.justify-content-center
         .col-2(v-for="n in notYet").text-center
            .segment.square
               div.hold
                  h4.text-uppercase {{n.client_name}}
                  p.m-2.text-info.load {{n.date_cmd | humane_date}}
               hr
               button.btn-block.btn.p-1.m-0.shadow(@click="makeVue(n.id,n.state)") {{n.state}}
</template>

<script>
   export default {
      name: "ReceptionList",
      data() {
         return {
            notYet: []
         }
      },
      mounted() {
         axios.get('/api/receptions/not-yet')
            .then(({data}) => this.notYet = data)
            .catch((err) => console.log(err))
      },
      methods: {
         makeVue(id, state) {
            if (state === 'ATTENTE')
               axios.put(`/api/receptions/${id}/state`, {state: 'VUE'})
                  .then(({data}) => {
                     this.$router.push({name: 'orderReception', params: {id: id}})
                  }).catch(err => {
                  console.log(err.response);
               })
            else
               this.$router.push({name: 'orderReception', params: {id: id}})

         },
      }
   }
</script>

<style scoped>
   .square {
      height: 150px;
      width: 150px;
   }

   .hfooter {
      width: 150px;
   }

   .hold {
      height: 84px;
   }
</style>
