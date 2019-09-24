<template>
   <div class="container">
      <big-title title="Liste Contenaires"/>
      <div class="row">

      </div>
      <div class="row justify-content-center" >
         <div class="col-10 my-2">
            <template v-for=" (c,j) in containers">
               <div class="tbl-header ">
                  <table class="table">
                     <thead class="bg-dark text-white text-center">
                     <tr>
                        <th>#</th>
                        <th>Numero de contenaire</th>
                        <th>Date De contenaire</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                     </thead>
                     <tbody class=" text-center">
                     <tr class="">
                        <td>1</td>
                        <td>{{c.n_dossier}}</td>
                        <td>{{c.date_facture | humane_date}}</td>
                        <td class="text-info">{{c.state}}</td>
                        <td class="text-info">
                           <button class="btn btn-danger btn-sm rounded-circle"><i class="fa fa-trash"></i></button>
                           <button class="btn btn-info btn-sm rounded-circle"><i class="fa fa-pen"></i></button>
                        </td>
                     </tr>
                     <tr class="segment">
                        <th>Numéro</th>
                        <th>Produit</th>
                        <th>Quantite de rapport</th>
                        <th>Quantite</th>
                        <th>
                           <button class="btn rounded-circle btn-sm btn-dark" data-toggle="collapse"
                                   :data-target="'#collapse_'+c.id" aria-expanded="false"
                                   :aria-controls="'collapse_'+c.id">
                              <i class="fa fa-chevron-down"></i>
                           </button>
                        </th>
                     </tr>
                     </tbody>
                  </table>
               </div>
               <div class="tbl-content collapse" :id="'collapse_'+c.id">
                  <table class="table text-center table-hover table-striped segment">
                     <tbody class="">
                     <tr v-for="(p,i) in c.product">
                        <td><span class="font-weight-bolder">{{i+1}}</span></td>
                        <td>{{p.name}}</td>
                        <td>{{p.pivot.qte_facture}}</td>
                        <td>{{p.pivot.rapport_qte}}</td>
                        <td>
                           <!--                        <button class="btn btn-link" @click="getDetail(p.id,j)">Détail</button>-->
                        </td>
                     </tr>
                     </tbody>
                  </table>
               </div>
            </template>
         </div>
         <div class="col segment filter">
            <big-title title="FILTER"/>
            <p>Add filter here !</p>
         </div>


      </div>
   </div>
</template>

<script>
    import BigTitle from "@/components/layouts/BigTitle";

    export default {
        name: "ListContainer",
        data() {
            return {
                containers: [],
                product: {},
                detail: []
            }
        },
        mounted() {
            axios.get('/api/arrivals?with=product&status=attent,vue,reception')
                .then(({data}) => {
                    this.containers = data
                })
                .catch(err => console.log(err.response))
        },
        methods: {
            // getDetail(id, index) {
            //     console.log(id)
            //     console.log(index)
            //
            //     axios.get(`/api/products/${id}?with=arrivals,provider`)
            //         .then(({data}) => {
            //             console.log(data)
            //             this.detail[index] = data
            //         })
            //         .catch(err => console.log(err.response))
            // },
        },
        components: {BigTitle}
    }
</script>

<style scoped>
   table {
      width: 100%;
      table-layout: fixed;
   }

   .tbl-content {
      max-height: 300px;
      overflow-x: auto;
      margin-top: 0;
      border: 1px solid rgba(255, 255, 255, 0.3);
   }

   .segment {
      margin: 0;
      border-bottom: 2px solid black;
   }
   .filter{
      max-height: 400px;
   }
</style>
