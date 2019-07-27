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
            .row
                .col
                    label(for="numTva") Numero TVA
                    input( type="text" class="form-control " id="numTva" aria-describedby="emailHelp" placeholder="TVA ..." v-model="numTva")
                .col
                    label(for="siret") Numero SIRET
                    input( type="text" class="form-control " id="siret" aria-describedby="emailHelp" placeholder="SIRET ..." v-model="nSiret")
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
                numTva: '',
                nSiret: '',
                id:null,
            }
        },
        methods: {
            sendToClient() {
                this.$emit('update', {
                    professional: {
                        firstName: this.firstName,
                        lastName: this.lastName,
                        numTva: this.numTva,
                        nSiret: this.nSiret,
                    }
                })
            },
            setProfessional(p){
                this.firstName = p.firstName;
                this.lastName = p.lastName;
                this.numTva = p.numTva;
                this.nSiret = p.nSiret;
            }
        },
    }
</script>

<style scoped>

</style>
