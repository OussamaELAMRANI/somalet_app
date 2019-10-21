<template>
   <div class="">
      <big-title title="Liste Contenaires"/>
      <div class="row justify-content-center">
         <div class="col-9 my-2">
            <table class="table table-striped table-hover">
               <thead class="bg-primary small text-white text-center">
               <tr>
                  <th>#</th>
                  <th>Numero de contenaire</th>
                  <th>Date De contenaire</th>
                  <th>Status</th>
                  <th>Action</th>
               </tr>
               </thead>
               <tbody class="text-center">
               <tr class="" v-for=" (c,j) in containers">
                  <td>{{j+1}}</td>
                  <td>{{c.n_dossier}}</td>
                  <td>{{c.date_facture | humane_date}}</td>
                  <td :class="(c.state != 'ATTENTE')? 'text-success font-weight-bolder':'text-primary'">{{c.state}}</td>
                  <td class="text-info">
                     <button class="btn btn-outline-danger btn-sm rounded-circle shadow"><i class="fa fa-trash my-1"></i></button>
                     <button class="btn btn-outline-secondary btn-sm rounded-circle shadow"><i class="fa fa-pen my-1"></i></button>
                     <button class="btn btn-primary btn-sm rounded-circle text-white shadow"><i class="fa fa-list my-1"></i></button>
                  </td>
               </tr>
               </tbody>
            </table>
         </div>
         <div class="col mx-2 segment filter shadow">
            <h5 class="text-primary">Filter par:</h5>
            <hr>
            <div class="form-group">
               <label for="">Contenaire</label>
               <input type="text" class="form-control rounded-pill" placeholder="Numéro du contenaire ...">
            </div>
            <div class="form-group">
               <label for="">Date d'enter</label>
               <input type="text" class="form-control rounded-pill" placeholder="Par Date ...">
            </div>
            <hr>
            <button class="btn btn-outline-primary btn-block rounded-pill">Filter</button>
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

<style lang="scss" scoped>
   $orange: #ff9f42;

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
      border-bottom: 2px solid $orange;
   }

   .filter {
      max-height: 700px;
      overflow: auto;
   }
</style>
