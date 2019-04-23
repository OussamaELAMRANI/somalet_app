<template lang="pug">
    form(@input="sendToProvider")
        .form-group
            h2.header Contact Fournisseur
            .dropdown-divider
            .row
                .col
                    label(for="portable") Numero portable
                    input( type="text" class="form-control " id="portable" placeholder="06 253 ..." v-model="cell" autofocus)
                .col
                    label(for="phone") Numero de Fixe
                    input( type="text" class="form-control " id="phone" placeholder="05 927 38 ..."  v-model="phone")

            .row.justify-content-center
                .col
                    label(for="mail") Email
                    input( type="text" class="form-control " id="mail" aria-describedby="emailHelp" placeholder="Email..."  v-model="email")
                    small.text-info exemple: mail@domain.com
            .row
                .col
                    label(for="skype") Skype
                    input( type="text" class="form-control " id="skype"  placeholder="Skype ici ..." v-model="skype" )
                .col
                    label(for="icp") ICP
                    input( type="text" class="form-control " id="icp" placeholder="ICP ..."  v-model="icp")

            .dropdown-divider
            slot
</template>

<script>
    export default {
        name: "ContactForm",
        data() {
            return {
                cell: '',
                phone: '',
                email: '',
                skype: '',
                icp: '',
            }
        },
        methods: {
            sendToProvider() {
                this.$emit('update', {
                    contact: {
                        cell: this.phone,
                        phone: this.fixe,
                        email: this.email,
                        skype: this.skype,
                        icp: this.icp,
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
