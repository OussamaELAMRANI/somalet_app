<template lang="pug">
    #show-provider.container
        h3 Information professionalles
        .dropdown-divider
        .row
            .col.m-2
                .text-uppercase
                    strong Societe :
                    span.ml-3 {{provider.steName}}
        .row
            .col.m-2
                .text-uppercase
                    strong TVA :
                    span.ml-3 {{provider.numTva}}
            .col.m-2
                .text-uppercase
                    strong Numero de Siret :
                    span.ml-3 {{provider.nSiret}}
        .row.justify-content-center
            .col-4.m-3
                .dropdown-divider
        h3 Information personnelles
        .dropdown-divider
        .row
            .col.m-2
                .text-uppercase
                    strong Prenom :
                    span.ml-3 {{provider.firstName}}
            .col.m-2
                .text-uppercase
                    strong Nom :
                    span.ml-3 {{provider.lastName}}
        .row
            .col.m-2
                p.text-uppercase
                strong Adresse :
                | {{provider.address}}
        .row
            .col.m-2
                .text-uppercase
                    strong Code Postal :
                    span.ml-3 {{provider.cp}}
            .col.m-2
                .text-uppercase
                    strong Ville :
                    span.ml-3 {{provider.city}}
            .col.m-2
                .text-uppercase
                    strong Paye :
                    span.ml-3 {{provider.country}}
        .row.justify-content-center
            .col-4.m-3
                .dropdown-divider
        h3 Contactes
        .dropdown-divider
        .row
            .col.m-2
                .text-uppercase
                    strong Numero de protable :
                    span.ml-3 {{provider.cell}}
            .col.m-2
                .text-uppercase
                    strong Numero de Fixe :
                    span.ml-3 {{provider.phone}}
        .row
            .col.m-2
                .text-uppercase
                    strong Email :
                    span.ml-3 {{provider.email}}
            .col.m-2
                .text-uppercase
                    strong Skype :
                    span.ml-3 {{provider.skype}}
            .col.m-2
                .text-uppercase
                    strong ICP :
                    span.ml-3 {{provider.icp}}

</template>

<script>
    export default {
        name: "showProvider",
        props: {
            provider: {
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
