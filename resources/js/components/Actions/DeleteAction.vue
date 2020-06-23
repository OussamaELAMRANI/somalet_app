<template>
   <alert-modal :title="title" id="bitch">
      <template slot="content">
         <h5 class="text-uppercase text-danger text-center">{{confirmation}}</h5>
      </template>
      <template slot="btn">
         <button class="btn btn-danger" @click="supp()" data-dismiss="modal">Supprimer</button>
      </template>
   </alert-modal>
</template>

<script>
   import AlertModal from "@/components/Modals/AlertModal";

   export default {
      name: "DeleteAction",
      components: {AlertModal},
      props: {
         title: {
            default: "Supperession d'un contenaire"
         },
         confirmation: {
            default: "Voullez-vous vraiment supprimer ?"
         },
         url: {},
      },
      methods: {
         supp() {
            axios.delete(`${this.url}`)
               .then(res => {
                  this.$notification.success("Supression en sccess");
                  this.$emit('isDeleted', true);
               })
               .catch(err => {
                  console.log(err)
                  this.$notification.error("Ce produit n'existe pas !")
               })
         },
      }
   }
</script>

<style scoped>

</style>
