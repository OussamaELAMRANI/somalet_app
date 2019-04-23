<template lang="pug">
    form(@input="sendToProvider")
        .form-group
            h2.header Informations Personnelles
            .dropdown-divider
            .row
                .col
                    label(for="name") Nom
                    input( type="text" class="form-control " id="name" aria-describedby="emailHelp" placeholder="Nom du fournisseur ici ..." v-model="name" autofocus)
                .col
                    label(for="lastename") Prenom
                    input( type="text" class="form-control " id="lastename" aria-describedby="emailHelp" placeholder="Prenom du fournisseur ici ..."
                    v-model="lastname")
            .row
                .col
                    label(for="address") Adresse
                    textarea( class="form-control " rows="4"  id="address" aria-describedby="emailHelp" placeholder="Adresse ici ..." v-model="address")
            .row
                .col
                    label(for="cp") Code postal
                    input( type="text" class="form-control" id="cp" aria-describedby="emailHelp" placeholder="Code postal ..." v-model="cp")
                .col
                    label(for="city") Ville
                    input( type="text" class="form-control" id="city" aria-describedby="emailHelp" placeholder="Ville ..." v-model="city")
                .col
                    label(for="country") Paye
                    input( type="text" class="form-control" id="country" aria-describedby="emailHelp" placeholder="Paye ..." v-model="country")

            .dropdown-divider
            slot
</template>

<script>
    export default {
        name: "PersonalForm",
        data() {
            return {
                name: '',
                lastname: '',
                address: '',
                cp: '',
                city: '',
                country: '',
            }
        },
        methods: {
            sendToProvider() {
                this.$emit('update', {
                    personal: {
                        firstName: this.name,
                        lastName: this.lastname,
                        address: this.address,
                        cp: this.cp,
                        city: this.city,
                        country: this.country,
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
