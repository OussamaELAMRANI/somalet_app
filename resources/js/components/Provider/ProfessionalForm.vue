<template lang="pug">
    form(@input="sendToProvider")
        .form-group
            h2.header Informations Professionelles
            .dropdown-divider
            label(for="ste") Nom de la societe
            input( type="text" class="form-control " id="ste" aria-describedby="emailHelp" placeholder="Societé ..." v-model="steName" autofocus name='society')
            //small.text-danger(v-show="errors.has('society')") Vous devez saisir le nom de la societé !
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
                steName: '',
                numTva: '',
                nSiret: '',
                id: null,
            }
        },
        methods: {
            sendToProvider() {
                //return this.$validator.validateAll().then((result) => {
                  //  if (result){
                        // this.$emit('getProducts', this.sendProduct());
                        this.$emit('update', {
                            professional: {
                                steName: this.steName,
                                numTva: this.numTva,
                                nSiret: this.nSiret,
                            }
                        })
                    //}

                    // return result;
                // });

            },
            setProfessional(p) {
                this.steName = p.steName;
                this.numTva = p.numTva;
                this.nSiret = p.nSiret;
            }
        },
    }
</script>

<style scoped>

</style>
