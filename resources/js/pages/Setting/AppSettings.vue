<template lang="pug">
   #app_setting
      bread-crumb
      h3.text-uppercase.text-secondary.my-3 Parramétre l'application d'ici
      hr
      .row.justify-content-center
         .col-3
            box(img='boxes/pantone.svg' title='Couleurs' h='h4' size='70px' subtitle='Gérer les couleur' link='settingColors' :hasState='null')
         .col-3
            box(img='boxes/measuring-tape.svg' title='Unités' h='h4' size='70px' subtitle='Régler les unités' link='settingUnity' :hasState='null')
         .col-3
            box(img='boxes/networking.svg' title='Catégories' h='h4' size='70px' subtitle='Ajouter les catégories' link='settingCategory' :hasState='null')

         .col-3
            box(img='boxes/money.svg' title='Paiéments' h='h4' size='70px' subtitle='Types de paiéments' link='settingPayments' :hasState='null')
      hr
      router-view
</template>

<script>
    import Box from "@/components/utils/Box";
    import BreadCrumb from "@/components/layouts/BreadCrumb";
    export default {
        name: "AppSettings",
       components: {BreadCrumb, Box}
    }
</script>

<style scoped>

</style>
