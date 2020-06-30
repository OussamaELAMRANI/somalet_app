<template lang="pug">
   .form-group
      label Selectionnez un client
      cool-select(:items='clients' :item-text="'nom'" item-value='id' @search='onSearch'
         name='client' placeholder="Nom ou Prénom" v-model='client_id' :loading="loading")
         template(slot='item' slot-scope='{ item:p }')
            p
               strong ({{p.id + ') '}}
               | {{ p.nom }}
         template(slot='no-data') {{ noData ? "Aucun client trouvé" : "Chercher par Nom ou Prénom" }}


</template>

<script>
   import {CoolSelect} from 'vue-cool-select'

   export default {
      name: "SelectClient",
      components: {CoolSelect},
      data() {
         return {
            clients: [],
            noData: false,
            loading: false,
            client_id: 0,
         }
      },
      methods: {
         onSearch(search) {
            // let rejectId = ""
            const lettersLimit = 2;
            this.noData = false;

            if (search.length < lettersLimit) {
               this.clients = [];
               this.loading = false;
               return;
            }
            this.loading = true;

            axios.get(`/api/clients/name/${search}`)
               .then(({data}) => {
                  this.clients = data;
                  if (data.length === 0)
                     this.noData = true

                  this.loading = false;
               })
         },
      },
      watch: {
         client_id: function (c) {
            this.$emit('sendClient', c);
         }
      }
   }
</script>

<style scoped>

</style>
