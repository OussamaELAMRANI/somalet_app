<template lang="pug">
    #show-client.container
        h3 Information professionalles
        .dropdown-divider
        .row
            .col.m-2
                .text-uppercase
                    strong Nom complet :
                    span.ml-3 {{client.nom}}
        .row
            .col.m-2
                .text-uppercase
                    strong TVA :
                    span.ml-3 {{client.numTva}}
            .col.m-2
                .text-uppercase
                    strong Numero de Siret :
                    span.ml-3 {{client.nSiret}}
        .row.justify-content-center
            .col-4.m-3
                .dropdown-divider
        h3 Information personnelles
        .dropdown-divider
        .row
            .col.m-2
                .text-uppercase
                    strong Prenom :
                    span.ml-3 {{client.firstName}}
            .col.m-2
                .text-uppercase
                    strong Nom :
                    span.ml-3 {{client.lastName}}
        .row
            .col.m-2
                p.text-uppercase
                strong Adresse :
                | {{client.address}}
        .row
            .col.m-2
                .text-uppercase
                    strong Code Postal :
                    span.ml-3 {{client.cp}}
            .col.m-2
                .text-uppercase
                    strong Ville :
                    span.ml-3 {{client.city}}
            .col.m-2
                .text-uppercase
                    strong Paye :
                    span.ml-3 {{client.country}}
        .row.justify-content-center
            .col-4.m-3
                .dropdown-divider
        h3 Contactes
        .dropdown-divider
        .row
            .col.m-2
                .text-uppercase
                    strong Numero de protable :
                    span.ml-3 {{client.cell}}
            .col.m-2
                .text-uppercase
                    strong Numero de Fixe :
                    span.ml-3 {{client.phone}}
        .row
            .col.m-2
                .text-uppercase
                    strong Email :
                    span.ml-3 {{client.email}}
            .col.m-2
                .text-uppercase
                    strong Skype :
                    span.ml-3 {{client.skype}}
            .col.m-2
                .text-uppercase
                    strong ICP :
                    span.ml-3 {{client.icp}}

</template>

<script>
    export default {
        name: "showClient",
        props: {
            client: {
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
