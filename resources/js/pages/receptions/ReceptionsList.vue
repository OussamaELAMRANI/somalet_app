<template>
   <div id="receptions_list">
      <div class="row">
         <div class="col-9 m-0 pr-0">
            <h4 class="text-uppercase m-3"> Liste des arrivages</h4>
            <div class="dropdown-divider"></div>
            <table class="table segment bg-puple text-white">
               <thead>
               <tr>
                  <th scope="col">N Facture</th>
                  <th scope="col">N Dossier</th>
                  <th scope="col">Date facture</th>
                  <th scope="col">Type</th>
                  <th scope="col">Prix de Fournisseur</th>
                  <th scope="col">ETAT de reception</th>
                  <th scope="col">Faite par</th>
                  <th scope="col">Actions</th>
               </tr>
               </thead>
            </table>

            <div class="list-arrival-content">
               <div v-if="searchArrivals === null">
                  <div class="row justify-content-center">
                     <div class="col-2">
                        <loading :animation-duration="6000" :size="40" color="#ff1d5e"/>
                     </div>
                  </div>
               </div>
               <div v-else-if="searchArrivals.length === 0">
                  <h6 class="text-center text-secondary m-5">Pas de résultat dans ce cas (Vide...) </h6>
               </div>

               <div v-else class="row segment align-items-center justify-content-center"
                    v-for="(c,index) in searchArrivals"
                    :key="index"
                    :id="c.id">
                  <div class="col-2">{{c.n_facture}}</div>
                  <div class="col-1">{{c.n_dossier}}</div>
                  <div class="col-2">{{c.date_facture}}</div>
                  <div class="col-2">
                     <small class="badge badge-primary">
                        {{c.type}}
                     </small>
                  </div>
                  <div class="col-2">{{c.price_devise}} DH</div>
                  <div class="col-1">
                            <span class="badge-danger badge">
                               {{c.state}}
                            </span>
                  </div>
                  <div class="col-2">{{c.user.username}}</div>
                  <template v-if="c.product.length >0">
                     <div class="col-12">
                        <hr>
                     </div>
                     <div class="col-1">
                        <button class="btn rounded-circle btn-sm btn-dark" data-toggle="collapse"
                                :data-target="'#collapse_'+c.id" aria-expanded="false" @click="updateState(c.id)"
                                :aria-controls="'collapse_'+c.id">
                           <i class="fa fa-chevron-down"></i>
                        </button>
                     </div>
                     <div class="col-12 justify-content-center mt-2 collapse"
                          :id="'collapse_'+c.id">
                        <table class="table  table-hover table-striped">
                           <thead class="bg-dark text-white">
                           <tr>
                              <th scope="col">Produit</th>
                              <th scope="col">Reference</th>
                              <th scope="col">Qte</th>
                              <th scope="col">Qte de Reception</th>
                              <!--                                    <th scope="col">ETAT de reception</th>-->
                              <th scope="col-2">Remaque</th>
                           </tr>
                           </thead>
                           <tbody>
                           <tr v-for="p in c.product">
                              <td>{{p.name.slice(0,10)}}</td>
                              <td>{{p.reference}}</td>
                              <td>
                                 <span class="badge">
                                 {{p.pivot.qte_facture}} X {{p.pivot.rapport_qte}}
                                 </span>

                                 {{p.pivot.rapport_qte*p.pivot.qte_facture}}
                              </td>
                              <td>
                                 <input type="text" class="form-control"
                                        placeholder="tapez ici la quantite ..." v-model="p.pivot.qte_reception">
                              </td>
                              <td>
                                 <input type="text" class="form-control"
                                        placeholder="tapez ici votre remarque ..." v-model="p.pivot.remark_reception">
                              </td>
                           </tr>
                           </tbody>
                           <tfoot class="bg-light">
                           <tr>
                              <th colspan="5" class="text-right">
                                 <button class="btn btn-success btn-sm" @click="sendReception(c.id,index)">
                                    <i class="fa fa-check"></i>
                                    VALIDER
                                 </button>
                              </th>
                           </tr>
                           </tfoot>
                        </table>
                     </div>

                  </template>


               </div>

            </div>


         </div>
         <!--            FILTER ========== -->
         <div class="col-3 m-0">
            <div class="">
               <form @submit.prevent="filterArrival">

                  <div class="segment bg-filter text-white bg-puple__light">
                     <p class="text-uppercase text-white text-right mb-3">
                        <i class="fa fa-filter"></i>
                        Filtrer
                     </p>
                     <div class="dropdown-divider"></div>

                     <div class="form-group">
                        <label>TYPE</label>
                        <select name="type" class="custom-select custom-select-sm mb-3" v-on:change="changeType">
                           <option value="all">TOUT</option>
                           <option value="national">NATIONAL</option>
                           <option value="international">INTERNATIONAL</option>
                        </select>
                     </div>
                     <div class="form-group">
                        <label>Tier par N Facture</label>
                        <input type="text" placeholder="N Facture ..." class="form-control"
                               v-model="search.n_fature" autofocus>
                     </div>
                     <div class="form-group">
                        <label>Tier par N Dossier</label>
                        <input type="text" placeholder="N Dossier ..." class="form-control"
                               v-model="search.n_dossier" autofocus>
                     </div>
                     <div class="dropdown-divider"></div>

                     <div class="form-group">
                        <label>Par date de Facture</label>
                        <datepicker v-model="search.date_facture" :language="fr" format="dd/MM/yyyy" name="entree"
                                    class="form-control" :calendar-button="true"
                                    calendar-button-icon="fa fa-calendar"/>
                     </div>
                     <div class="dropdown-divider"></div>

                     <button class="btn btn-outline-light container-fluid" @click="filterArrival">
                        <i class="fa fa-filter"></i>
                        Filter
                     </button>
                  </div>
               </form>
            </div>

         </div>

      </div>

   </div>

</template>

<script>
   import {SelfBuildingSquareSpinner} from 'epic-spinners'
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import moment from 'moment'

   export default {
      name: "ReceptionsList",
      data() {
         return {
            fr,
            factorType: 'NATIONAL',
            search: {
               n_fature: '',
               n_dossier: '',
               date_facture: moment().format('DD/MM/YYYY'),
            },
            arrivals: [],
            searchArrivals: [],
         }
      },
      mounted() {
         axios.get('/api/receptions', {params: {with: 'user,provider,product'}})
            .then(res => {
               const {data} = res
               this.arrivals = data
               this.searchArrivals = data
               console.log(data)
            })
            .catch(err => console.log(err.response))
      },
      methods: {
         changeType(ev) {
            let val = ev.target.options[ev.target.options.selectedIndex].value
            console.log(val)
            this.factorType = val
         },
         filterArrival() {
            //par facture
            // if (this.search.n_fature)
            axios.get('/api/arrivals',
               {
                  params: {
                     facture: this.search.n_fature,
                     dossier: this.search.n_dossier,
                     with: 'user',
                     types: this.factorType
                  }
               })
               .then(res => {
                  const {data} = res
                  console.log(data)
                  this.searchArrivals = data
               })
               .catch(err => console.log(err.response))
            // else
            //     this.searchArrivals = this.arrivals
         },
         getArrival(a) {

         },
         getIdToDelete(a) {

         },
         toUpdate(a) {

         },
         sendReception(arrival_id, index) {
            const products = this.arrivals[index].product;
            axios.put('/api/arrivals', {arrival_id, products})
               .then(({data}) => {
                  console.log(data)
               })
         },
         updateState(id) {
            axios.put(`/api/arrivals/state/${id}`, {state: 'VUE'})
               .then(({data}) => {
                  console.log(data)
                  _.forEach(this.searchArrivals, (arr) => {
                     if(arr.id === id)
                        arr.state = 'VUE'
                  })
               })
         }
      },
      components: {
         'loading': SelfBuildingSquareSpinner,
         Datepicker
      }
   }
</script>

<style lang="scss" scoped>
   $purpley: #2fbacb;
   .bg-puple {
      background: $purpley;
   }

   .bg-puple__dark {
      background: darken($purpley, 10%);
   }

   .bg-puple__light {
      background: darken($purpley, 10%);
   }

   .segment {
      border-top: 2px solid #595c4f;
   }

   .filter__fix {
      box-shadow: 0 2px 4px rgba(156, 87, 248, 0.5);
      max-width: 12em;
      top: 0;
      z-index: 100;
      /*background: #9c5;*/
      /*position: fixed;*/
   }
</style>
