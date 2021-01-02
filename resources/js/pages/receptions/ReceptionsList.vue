<template>
   <div id="receptions_list">
      <div class="row justify-content-center">
         <div class="col-12"><h4 class="text-uppercase m-3"> Liste des arrivages</h4>
            <hr>
         </div>
         <div class="col-10 p-2 m-1  pr-0">
            <div
               class="row text-center p-2 m-2 bg-primary text-white text-center align-items-center justify-content-center">
               <div class="col-2">N Contenaire</div>
               <div class="col-2">Date</div>
               <div class="col-2">Type</div>
               <div class="col-2">Etat de Reception</div>
            </div>
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
                  <div class="col-2 text-left">{{ c.n_dossier }}</div>
                  <div class="col-2">{{ c.date_facture }}</div>
                  <div class="col-2 text-secondary">{{ c.type }}</div>
                  <div class="col-2 text-center"
                       :class="(c.state == 'RECEPTION')? 'text-success font-weight-bolder':'text-primary'">
                     {{ c.state }}
                  </div>
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
                        <table class="table  table-hover table-striped table-bordered text-center">
                           <thead class="bg-success text-white">
                           <tr>
                              <th scope="col">Produit</th>
                              <th scope="col">Reference</th>
                              <th scope="col">Quantité</th>
                              <th scope="col">Qte de Reception</th>
                              <th scope="col">Rapport</th>
                              <th scope="col">Qte Total</th>
                              <!--                              <th scope="col-2">Qte de Rapport</th>-->
                           </tr>
                           </thead>
                           <tbody>
                           <tr v-for="p in c.product">
                              <td>{{ p.name }}</td>
                              <td>{{ p.reference }}</td>
                              <td class="text-primary font-weight-bolder">
                                 {{ p.pivot.qte_facture }} {{ showUnit(p.unit) }}
                              </td>
                              <td>
                                 <input type="number" class="form-control"
                                        placeholder="tapez ici la Quantite ..." v-model.number="p.pivot.qte_reception">
                              </td>
                              <td class="text-success font-weight-bolder">
                                 {{ p.pivot.rapport_qte }} {{ showUnit(p.unit, false) }}
                              </td>
                              <td>
                                 {{ p.pivot.qte_facture * p.pivot.rapport_qte }} {{ showUnit(p.unit, false) }}s
                              </td>

                              <!--                              <td>-->
                              <!--                                 <input type="number" class="form-control"-->
                              <!--                                        placeholder="tapez Quantité de Rapport ..."-->
                              <!--                                        v-model.number="p.pivot.qte_rapport_reception">-->
                              <!--                              </td>-->
                           </tr>
                           </tbody>
                           <tfoot class="segment">
                           <tr>
                              <th colspan="7" class="text-right">
                                 <button class="btn btn-success" @click="sendReception(c.id,index)">
                                    <i class="fa fa-check"></i>
                                    VALIDER
                                 </button>
                                 <button class="btn btn-primary" @click="validAllReceptions(c.id,index)">
                                    <i class="fa fa-check"></i>
                                    TOUT EST VALIDE
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
         <!--         <div class="col mx-2 shadow filter segment">-->
         <!--            <h5 class="text-uppercase text-primary mb-3">-->
         <!--               <i class="fa fa-filter"></i>-->
         <!--               Filtrer-->
         <!--            </h5>-->
         <!--            <hr>-->
         <!--            <form @submit.prevent="filterArrival">-->
         <!--               <div class="form-group">-->
         <!--                  <label>TYPE</label>-->
         <!--                  <select name="type" class="form-control bg-primary text-white rounded mb-3" v-on:change="changeType">-->
         <!--                     <option value="all">TOUT</option>-->
         <!--                     <option value="national">NATIONAL</option>-->
         <!--                     <option value="international">INTERNATIONAL</option>-->
         <!--                  </select>-->
         <!--               </div>-->
         <!--               <hr>-->
         <!--               <div class="form-group">-->
         <!--                  <label>Tier par N Facture</label>-->
         <!--                  <input type="text" placeholder="N Facture ..." class="form-control rounded-pill"-->
         <!--                         v-model="search.n_fature" autofocus>-->
         <!--               </div>-->
         <!--               <div class="form-group">-->
         <!--                  <label>Tier par N Dossier</label>-->
         <!--                  <input type="text" placeholder="N Dossier ..." class="form-control rounded-pill"-->
         <!--                         v-model="search.n_dossier" autofocus>-->
         <!--               </div>-->
         <!--               <div class="form-group">-->
         <!--                  <label>Par date de Facture</label>-->
         <!--                  <datepicker v-model="search.date_facture" :language="fr" format="dd/MM/yyyy" name="entree"-->
         <!--                              class="form-control rounded-pill" :calendar-button="true"-->
         <!--                              calendar-button-icon="fa fa-calendar"/>-->
         <!--               </div>-->
         <!--               <hr>-->
         <!--               <button class="btn btn-outline-primary rounded-pill container-fluid" @click="filterArrival">-->
         <!--                  <i class="fa fa-filter"></i>-->
         <!--                  Filter-->
         <!--               </button>-->
         <!--            </form>-->

         <!--         </div>-->

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
      axios.get('/api/receptions', {params: {with: 'user,provider,product,unit'}})
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
         this.factorType = ev.target.options[ev.target.options.selectedIndex].value
      },
      filterArrival() {
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
      },
      calcTotal(p) {
         const total = Number(p.qte_reception) * Number(p.qte_rapport_reception)
         return `<strong>${total}</strong>`
      },
      sendReception(arrival_id, index) {
         const articles = this.arrivals[index].product;
         const products = articles.map(p => {
            p.pivot.qte_rapport_reception = p.pivot.rapport_qte
            return p;
         })
         axios.put('/api/arrivals', {arrival_id, products})
            .then(({data}) => {
               this.$notification.success("La receptions des quantité a été bien faite 👏")
               this.$router.push('/')
            })
      },
      validAllReceptions(arrival_id, index) {
         // const products = this.arrivals[index].product;
         axios.put(`/api/arrivals/${arrival_id}/valid-reception`)
            .then(({data}) => {
               this.$notification.success("Toutes les receptions ont été bien faite 👏")
               this.$router.push('/')

            })
      },
      updateState(id) {
         axios.put(`/api/arrivals/state/${id}`, {state: 'VUE'})
            .then(({data}) => {
               _.forEach(this.searchArrivals, (arr) => {
                  if (arr.id === id)
                     arr.state = 'VUE'
               })
            })
            .catch(error => console.log(error.response))
      },
   },
   components: {
      'loading': SelfBuildingSquareSpinner,
      Datepicker
   }
}
</script>

<style lang="scss" scoped>
$purpley: #2fbacb;
$purple: #ff9f42;


.filter__fix {
   box-shadow: 0 2px 4px rgba(156, 87, 248, 0.5);
   max-width: 12em;
   top: 0;
   z-index: 100;
   /*background: #9c5;*/
   /*position: fixed;*/
}

.segment {
   margin: 0;
   border-bottom: 2px solid $purple;
}

</style>
