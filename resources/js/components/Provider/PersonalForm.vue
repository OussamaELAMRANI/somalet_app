<template lang="pug">
    form(@input="sendToProvider")
        .form-group
            h2.header Informations Personnelles
            .dropdown-divider
            .row
                .col
                    label(for="firstName") Nom
                    input( type="text" class="form-control " id="firstName"  placeholder="Nom du fournisseur ici ..." v-model="firstName" autofocus)
                .col
                    label(for="lastName") Prenom
                    input( type="text" class="form-control " id="lastName"  placeholder="Prenom du fournisseur ici ..."
                    v-model="lastName")
            .row
                .col
                    label(for="address") Adresse
                    textarea( class="form-control " rows="4"  id="address" placeholder="Adresse ici ..." v-model="address")
            .row
                .col
                    label(for="cp") Code postal
                    input( type="text" class="form-control" id="cp" placeholder="Code postal ..." v-model="cp")
                .col
                    label(for="city") Ville
                    input( type="text" class="form-control" id="city" placeholder="Ville ..." v-model="city")
                .col
                    label(for="country") Paye
                    input( type="text" class="form-control" id="country" placeholder="Paye ..." v-model="country")

            .dropdown-divider
            slot
</template>

<script>
    import {mapActions} from 'vuex'
    export default {
        name: "PersonalForm",
        data() {
            return {
                firstName: '',
                lastName: '',
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
                        firstName: this.firstName,
                        lastName: this.lastName,
                        address: this.address,
                        cp: this.cp,
                        city: this.city,
                        country: this.country,
                    }
                })
            },
            setPersonal(p){
                this.firstName = p.firstName;
                this.lastName = p.lastName;
                this.address = p.address;
                this.cp = p.cp;
                this.city = p.city;
                this.country = p.country;
            }
        }
    }
</script>

<style scoped>

</style>
