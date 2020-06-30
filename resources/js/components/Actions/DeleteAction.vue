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
                  console.log(res.data);
                  this.$notification.success("Supression en sccess");
                  this.$emit('isDeleted', true);
               })
               .catch(err => {
                     const {data} = err.response;
                     console.log(data)
                     this.$notification.error(data.message)
                  }
               )
         },
      }
   }
</script>

<style scoped>

</style>
