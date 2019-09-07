<template>
   <div id="stock_tables">

      <table class="table table-hover table-striped" v-for="(arr , k) in arrivals">
         <thead class="thead-dark text-center table-bordered">
         <tr>
            <th v-for="h in headTable">{{h}}</th>
            <th>Marge</th>
            <th>TVA</th>
            <th>
               <button class="btn btn-success" @click="ValidContainer(arr.id,k)">VALIDER</button>
            </th>
         </tr>
         </thead>
         <tbody class="table-bordered table-striped text-center table-hover">
         <tr class="subTable" style=" padding: 0;">
            <td class="d-flex justify-content-center">
               <div class="bar"></div>
            </td>
            <td>{{arr.n_facture}}</td>
            <td>{{arr.n_dossier}}</td>
            <td>{{arr.date_facture}}</td>
            <td>
               <input type="number" class="form-control" placeholder="1% marge .." v-model="calculation[k].marge">
            </td>
            <td>
               <input type="number" class="form-control" placeholder="20% TVA .." v-model="calculation[k].tva">
            </td>
            <td>
               <button class="btn rounded-circle btn-sm btn-primary" data-toggle="collapse"
                       :data-target="'#collapse_'+arr.id" aria-expanded="false" @click="switchCollapse(arr.id)"
                       :aria-controls="'collapse_'+arr.id">
                  <i class="fa fa-chevron-down"></i>
               </button>
            </td>
         </tr>
         <tr>
            <td :colspan="headTable.length +3 " class="" style="padding: 2px;background-color: #3490DC"></td>
         </tr>
         <template v-if="collapse[k].collapse">
            <tr class="bg-primary text-white text-uppercase">
               <th></th>
               <th>Produit</th>
               <th>Designation</th>
               <th>Quantite</th>
               <th>Qte Magazine</th>
               <th>Cout de Revient HT</th>
               <th>Prix de vente TTC</th>
            </tr>
            <tr class="subTable" v-for="p in arr.product">
               <td class="d-flex justify-content-center">
                  <div class="bar"></div>
               </td>
               <td>{{p.reference}}</td>
               <td>{{p.name}}</td>
               <td>
                  <span class="badge">
                     {{p.pivot.qte_facture}} X
                     {{p.pivot.rapport_qte}} =
                  </span>
                  <strong>
                     {{p.pivot.qte_facture * p.pivot.rapport_qte}}
                  </strong>
               </td>
               <td
                  :style="{backgroundColor: (p.pivot.qte_reception >= (p.pivot.qte_facture * p.pivot.rapport_qte))? '#80ff8e' :'#ff9b15'}">
                  {{p.pivot.qte_reception}}
               </td>
               <td>{{p.pivot.price_unit_ht}}</td>
               <td>{{sellPrice(p.pivot.price_unit_ht,calculation[k].tva,calculation[k].marge)}}</td>
            </tr>
            <tr>
               <td :colspan="headTable.length +3 " class="" style="padding: 2px;background-color: #3490DC"></td>
            </tr>
         </template>

         </tbody>
      </table>
   </div>

</template>

<script>
   export default {
      name: "StockTable",
      props: {
         headTable: {},
         // arrival: {}
      },
      data() {
         return {
            arrivals: [],
            collapse: [],
            calculation: [],
         }
      },
      mounted() {
         axios.get('/api/receptions/no-valid')
            .then(res => {
               const {data} = res
               // const len = data.length
               this.arrivals = data
               _.forEach(data, (a) => {
                  this.collapse.push({id: a.id, collapse: false})
                  this.calculation.push({id: a.id, marge: 0, tva: 20, product: a.product})
               })
            })
            .catch(err => console.log(err.response))

      },
      methods: {
         switchCollapse(id) {

            _.filter(this.collapse, (coll) => {
               if (coll.id == id)
                  coll.collapse = true
               else
                  coll.collapse = false
            })
         },
         sellPrice(price, tva, marge) {
            let cr = Number(price);
            let mrg = (cr * Number(marge)) / 100;
            let sell_priceHT = mrg + cr;
            let ttc = (sell_priceHT * Number(tva)) / 100;

            return Number(ttc + sell_priceHT).toFixed(2);
         },
         getPriceTTC(price, tva) {
            price = Number(price);
            tva = (Number(tva) * price) / 100;

            return Number(price + tva).toFixed(2)
         },
         ValidContainer(arrival_id, index) {
            const calcule = this.calculation[index];
            const marge = calcule.marge;
            const products = calcule.product;

            _.forEach(products, (p) => {
               p.pivot.sell_price = Number(this.sellPrice(p.pivot.price_unit_ht, calcule.tva, calcule.marge))
               p.pivot.price_unit_ttc = this.getPriceTTC(p.pivot.price_unit_ht, calcule.tva)
            });

            axios.put(`/api/receptions/validate/${arrival_id}`, {products, marge})
               .then(({data}) => {
                  console.log(data)
               })
               .catch(err => console.log(err.response))
         }
      },
      computed: {}
   }
</script>

<style lang="scss" scoped>
   .subTable {
      padding: 0;
      margin: 0;

      td:first-child {
         padding: 0;
         border: 0;
      }
   }

   td:first-child, th:first-child {
      padding: 0;
      border: 0;
      width: 10px;
      box-shadow: 0 0 10px 2px gray;
   }

   .bar {
      height: 60px;
      background: #3490DC;
      width: 100%;
      /*border: 2px black solid;*/
   }


   /*.list-enter {*/
   /*   opacity: 0;*/
   /*}*/
   /*.list-enter-active {*/
   /*   animation: slide-in .5s ease-out forwards;*/
   /*}*/
   /*.list-leave-to, .list-leave-active {*/
   /*   opacity: 0;*/
   /*   animation: slide-out .5s ease-out forwards;*/
   /*}*/
   /*@keyframes slide-in {*/
   /*   from { height: 0; } to { height: 40px; }*/
   /*}*/
   /*@keyframes slide-out {*/
   /*   from { height: 100px; } to { height: 0; }*/
   /*}*/

</style>
