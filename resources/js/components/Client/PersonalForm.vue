<template lang="pug">
    form(@input="sendToClient")
        .form-group
            h2.header Informations Personnelles
            .dropdown-divider
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
    import {mapActions} from 'vuex'
    export default {
        name: "PersonalForm",
        data() {
            return {
                address: '',
                cp: '',
                city: '',
                country: '',
            }
        },
        methods: {
            sendToClient() {
                this.$emit('update', {
                    personal: {
                        address: this.address,
                        cp: this.cp,
                        city: this.city,
                        country: this.country,
                    }
                })
            },
            setPersonal(p){
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
