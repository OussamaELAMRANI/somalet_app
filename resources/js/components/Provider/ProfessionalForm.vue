<template lang="pug">
    form(@input="sendToProvider")
        .form-group
            h2.header Informations Professionelles
            .dropdown-divider
            label(for="ste") Nom de la societe
            input( type="text" class="form-control " id="ste" aria-describedby="emailHelp" placeholder="Societe ..." v-model="steName" autofocus)
            .row
                .col
                    label(for="ntva") Numero TVA
                    input( type="text" class="form-control " id="ntva" aria-describedby="emailHelp" placeholder="TVA ..." v-model="nTva")
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
                steName: '',
                nTva: '',
                nSiret: '',
            }
        },
        methods: {
            sendToProvider() {
                this.$emit('update', {
                    professional: {
                        steName: this.steName,
                        numTva: this.nTva,
                        nSiret: this.nSiret,
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
