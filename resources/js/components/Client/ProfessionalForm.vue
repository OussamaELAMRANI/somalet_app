<template lang="pug">
   form(@input="sendToClient")
      .form-group
         h2.header Informations Professionelles
         .dropdown-divider
         .row
            .col
               label(for="firstName") Nom
               input( type="text" class="form-control " id="firstName" placeholder="Nom du client ici ..." v-model="firstName" autofocus)
            .col
               label(for="lastName") Prenom
               input( type="text" class="form-control " id="lastName"  placeholder="Prenom du client ici ..."
                  v-model="lastName")
            .col
               label(for="lastName") Ancien Solde
               input( type="number" class="form-control " id="old_sold" placeholder="Solde de départ"
                  v-model.number="old_sold")
         .dropdown-divider
         slot

</template>

<script>
   export default {
      name: "ProfessionalForm",
      data() {
         return {
            firstName: '',
            lastName: '',
            old_sold: 0,
            id: null,
         }
      },
      methods: {
         sendToClient() {
            this.$emit('update', {
               professional: {
                  firstName: this.firstName,
                  lastName: this.lastName,
                  old_sold: this.old_sold,
               }
            })
         },
         setProfessional(p) {
            this.firstName = p.firstName;
            this.lastName = p.lastName;
            this.old_sold = p.old_sold;
         }
      },
   }
</script>

<style scoped>

</style>
