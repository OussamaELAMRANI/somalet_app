<template>
   <div class="shoes-size container">
      <alert-modal title="Supperession d'une Pointure" id="bitch">
         <template slot="content">
            <h5 class="text-uppercase text-danger text-center">
               Voullez-vous vraiment supprimer cette pointure ?
            </h5>
         </template>
         <template slot="btn">
            <button class="btn btn-danger" @click="supp()" data-dismiss="modal">Supprimer</button>
         </template>
      </alert-modal>

      <h3>Pointures des chaussures</h3>
      <div class="segment">
         <div class="form-group">
            <label for="size">Pointure</label>
            <input type="number" class="form-control" id="size" v-model.number="shoeSize">
            <small class="text-danger">{{err}}</small>
         </div>
         <div class="form-group text-right">
            <button class="btn btn-success" @click="addSize">
               <i class="fa fa-plus"></i>
               Ajouter
            </button>
         </div>
      </div>
      <TableLayout :headTable="headTable" :data="sizes">
         <tr v-for="(s,i) in sizes">
            <td>{{i+1}}</td>
            <td><strong>{{s.size }}</strong></td>
            <td>
               <button class="btn btn-sm btn-danger" @click="setDelete(s.id)"
                       data-target="#bitch" data-toggle="modal">
                  <i class="fa fa-trash"></i>
               </button>
            </td>
         </tr>
      </TableLayout>
   </div>
</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";
   import AlertModal from "@/components/Modals/AlertModal";

   export default {
      name: "Shoes-size",
      components: {AlertModal, TableLayout},
      data() {
         return {
            url: '/api/products/sizes',
            headTable: ['#', 'Pointures', 'Actions'],
            sizes: null,
            shoeSize: null,
            err: null,
            ElementIdToDelete: 0
         }
      },
      mounted() {
         axios.get(this.url)
            .then(({data}) => {
               this.sizes = data
            })
            .catch(err => console.log(err))
      },
      methods: {
         addSize() {
            if (this.sizes.some(e => e.size === this.shoeSize) || this.shoeSize == null) {
               this.err = 'Erreur de pointure, cette poiture déja existe !';
               return
            }
            this.err = null;
            axios.post(this.url, {size: this.shoeSize})
               .then(({data}) => {
                  this.sizes.unshift(data);
                  this.$notification.success('Cette pointure a été bien ajouté.')
               })
               .catch(err => {
                  const {data} = err.response
                  this.err = data.err;
               })
         },
         supp() {
            let id = this.ElementIdToDelete;
            axios.delete(`${this.url}/${id}`)
               .then(res => {
                  this.sizes = this.sizes.filter((s) => s.id !== id)
               })
               .catch(err => {
                  this.$notification.error("Cette pointure n'existe pas !")
                  console.log(err)
               })
         },
         setDelete(id) {
            this.ElementIdToDelete = id
         }
      }
   }
</script>

<style scoped>

</style>
