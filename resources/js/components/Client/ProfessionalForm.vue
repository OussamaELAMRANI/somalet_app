<template lang="pug">
    form(@input="sendToClient")
        .form-group
            h2.header Informations Professionelles
            .dropdown-divider
            .row
                .col
                    label(for="firstName") Nom
                    input( type="text" class="form-control " id="firstName" aria-describedby="emailHelp" placeholder="Nom du client ici ..." v-model="firstName" autofocus)
                .col
                    label(for="lastName") Prenom
                    input( type="text" class="form-control " id="lastName" aria-describedby="emailHelp" placeholder="Prenom du client ici ..."
                        v-model="lastName")
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
                id:null,
            }
        },
        methods: {
            sendToClient() {
                this.$emit('update', {
                    professional: {
                        firstName: this.firstName,
                        lastName: this.lastName,
                    }
                })
            },
            setProfessional(p){
                this.firstName = p.firstName;
                this.lastName = p.lastName;
            }
        },
    }
</script>

<style scoped>

</style>
