<template>
   <div id="arrival_form" class="segment">
      <form ref="entete" class=" container">
         <big-title title="Entête Facture"/>
         <div class="row" v-if="providers.length === 0">
            <div class="col">
               <router-link :to="{name:'newProvider'}" class="btn-sm btn btn-outline-success">Nouveau Fournisseur
               </router-link>
            </div>
         </div>
         <template v-else>
            <div class="row ">
               <div class="col">
                  <label>Fournisseur : </label>
                  <cool-select :items="providers" item-text="steName" item-value="id" name="provider"
                               v-model="newArr.provider_id" />
<!--                  <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('provider')"></i>-->
<!--                  <span class="help text-danger" v-show="errors.has('provider')">{{ messageErr }}</span>-->
               </div>
               <div class="col">
                  <div class="form-group">
                     <label>Numero Facture</label>
                     <input class="form-control" name="n_facture" type="text" v-model="newArr.n_facture"/>
<!--                     <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('n_facture')"></i>-->
<!--                     <span class="help text-danger" v-show="errors.has('n_facture')">{{ messageErr }}</span>-->
<!--                     <span class="help text-danger">{{ error.facture }}</span>-->
                  </div>
               </div>
               <div class="col">
                  <div class="form-group">
                     <label>Numero du dossier</label>
                     <input class="form-control" name="n_dossier" type="text" v-model="newArr.n_dossier">
<!--                     <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('n_dossier')"></i>-->
<!--                     <span class="help text-danger" v-show="errors.has('n_dossier')">{{ messageErr }}</span>-->
                  </div>
               </div>
            </div>
            <div class="row ">
               <div class="col">
                  <div class="form-group">
                     <label>Date de la Facture :</label>
                     <datepicker :format="customFormatter" :language="fr" :monday-first="true"
                                 calendar-button-icon="fa fa-user" class="form-control" name="date_picker"
                                 v-model="newArr.date_facture"/>
                  </div>
               </div>
               <div class="col">
                  <div class="form-group">
                     <label for="">Type de l'arrivage :</label>
                     <cool-select :items="types" name="type" v-model="newArr.type"/>
<!--                     <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('type')"></i>-->
<!--                     <span class="help text-danger" v-show="errors.has('type')">{{ messageErr }}</span>-->
                  </div>
               </div>
               <div class="col">
                  <div class="form-group">
                     <label for="">Prix fournisseur :</label>
                     <div class="input-group">
                        <input :disabled="newArr.type==='INTERNATIONAL'" class="form-control" name="prix_fournisseur"
                               type="number" v-model="newArr.price_provider" />
                        <div class="input-group-append">
                           <div class="input-group-text">DH</div>
                        </div>
                     </div>
<!--                     <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('prix_fournisseur')"></i>-->
<!--                     <span class="help text-danger" v-show="errors.has('prix_fournisseur')">{{ messageErr }}</span>-->
                  </div>
               </div>
            </div>
            <div class="row " v-if="newArr.type === 'INTERNATIONAL'">
               <div class="col">
                  <div class="form-group">
                     <label for="">Prix en devise :</label>
                     <input class="form-control" type="number" v-model="inter.price_devise"/>
                  </div>
               </div>
               <div class="col">
                  <div class="form-group">
                     <label for="">Cours de change :</label>
                     <input class="form-control" type="number" v-model="inter.cours_change"/>
                  </div>
               </div>
            </div>
            <!--            <div class="row">-->
            <!--               <div class="col">-->
            <!--                  <div class="form-grouptype">-->
            <!--                     <i class="fa fa-exclamation-triangle text-danger" v-show="formErrors != null"></i>-->
            <!--                     <span class="help text-danger" v-show="formErrors != null">{{ formErrors }}</span>-->
            <!--                  </div>-->
            <!--               </div>-->
            <!--            </div>-->

            <!--  name="chargesForm"  =================================== INTERNATIONALISATION CASE =================================== -->
            <div v-show="newArr.type=='INTERNATIONAL'" ref="chargesForm">
               <h4 class="text-uppercase text-center text-primary mb-3">Partie des charges :</h4>
               <div class="dropdown-divider"></div>
               <div class="row">
                  <div class="col half-cost">
                     <div class="row" name="transitaire">
                        <div class="col">
                           <div class="form-group">
                              <label>transitaire :</label>
                              <input class="form-control" type="number" v-model="inter.transitaire"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>transitaire_tva :</label>
                              <input class="form-control" type="number" v-model="inter.transitaire_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <p class="text-uppercase">transitaire ttc</p>
                              <p class="text-primary font-weight-bold">
                                 {{inter.transitaire_ttc}}
                              </p>
                           </div>
                        </div>
                     </div>
                     <div class="row" name="transport">
                        <div class="col">
                           <div class="form-group">
                              <label>transport :</label>
                              <input class="form-control" type="number" v-model="inter.transport"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>transport tva :</label>
                              <input class="form-control" type="number" v-model="inter.transport_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <p class="text-uppercase">transport ttc</p>
                           <p class="text-primary font-weight-bold">{{inter.transport_ttc }}</p>
                        </div>
                     </div>
                     <div class="row" name="magazinage">
                        <div class="col">
                           <div class="form-group">
                              <label>magazinage :</label>
                              <input class="form-control" type="number" v-model="inter.magazinage"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>magazinage tva :</label>
                              <input class="form-control" type="number" v-model="inter.magazinage_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <p class="text-uppercase">magazinage ttc</p>
                              <p class="text-primary font-weight-bold">{{inter.magazinage_ttc}}</p>
                           </div>
                        </div>
                     </div>
                     <div class="row" name="magazinage">
                        <div class="col">
                           <div class="form-group">
                              <label for="">Douane :</label>
                              <input class="form-control" type="number" v-model="inter.douane"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label for="">Douane TVA :</label>
                              <input class="form-control" type="number" v-model="inter.douane_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <p class="text-uppercase">Douane TTC :</p>
                              <p class="text-primary font-weight-bolder">{{inter.douane_ttc}}</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col">
                     <div class="row" name="surestaries">
                        <div class="col">
                           <div class="form-group">
                              <label for="">surestaries :</label>
                              <input class="form-control" type="number" v-model="inter.surestaries"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>surestaries tva</label>
                              <input class="form-control" type="number" v-model="inter.surestaries_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <p class="text-uppercase">surestaries ttc :</p>
                           <p class="text-primary font-weight-bold">{{inter.surestaries_ttc}}</p>
                        </div>
                     </div>
                     <div class="row" name="surestaries">
                        <div class="col">
                           <div class="form-group">
                              <label>manutension :</label>
                              <input class="form-control" type="number" v-model="inter.manutension"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>manutension_tva :</label>
                              <input class="form-control" type="number" v-model="inter.manutension_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <p class="text-uppercase">manutension ttc</p>
                           <p class="text-primary font-weight-bold">{{inter.manutension_ttc}}</p>
                        </div>
                     </div>
                     <div class="row" name="fret">
                        <div class="col">
                           <div class="form-group">
                              <label>fret :</label>
                              <input class="form-control" type="number" v-model="inter.fret"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>fret_tva :</label>
                              <input class="form-control" type="number" v-model="inter.fret_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <p class="text-uppercase">fret ttc</p>
                           <p class="text-primary font-weight-bold">{{inter.fret_ttc}}</p>
                        </div>
                     </div>
                     <div class="row" name="autre">
                        <div class="col">
                           <div class="form-group">
                              <label>autres :</label>
                              <input class="form-control" type="number" v-model="inter.autres"/>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label>autres tva :</label>
                              <input class="form-control" type="number" v-model="inter.autres_tva"/>
                           </div>
                        </div>
                        <div class="col">
                           <p class="text-uppercase">autres ttc</p>
                           <p class="text-primary font-weight-bold">{{inter.autres_ttc}}</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="dropdown-divider"></div>
               <div class="row" name="cout_revient">
                  <div class="col">
                     <div class="form-group">
                        <label>cout de revient</label>
                        <input class="form-control" disabled type="number" v-model="inter.cout_revient"/>
                     </div>
                  </div>
                  <div class="col">
                     <div class="form-group">
                        <label>cout de revient tva :</label>
                        <input class="form-control" disabled type="number" v-model="inter.cout_revient_tva"/>
                     </div>
                  </div>
                  <div class="col">
                     <div class="form-group">
                        <label>cout_revient ttc :</label>
                        <input class="form-control" disabled type="number" v-model="inter.cout_revient_ttc"/>
                     </div>
                  </div>
               </div>

            </div>
         </template>
      </form>
   </div>
</template>

<script>
   import {CoolSelect} from 'vue-cool-select'
   import moment from 'moment'
   import Datepicker from 'vuejs-datepicker';
   import {fr} from 'vuejs-datepicker/dist/locale'
   import BigTitle from "@/components/layouts/BigTitle";


   export default {
      name: "ArrivalNew",
      data() {
         return {
            fr,
            newArr: {
               provider_id: null,
               n_facture: '',
               n_dossier: '',
               date_facture: moment().format('DD/MM/YYYY'),
               price_provider: '',
               type: 'NATIONAL',
            },
            inter: {
               price_devise: 1,
               cours_change: 1,
               //Variables des charges
               transitaire: 0,
               transitaire_tva: 0,
               transitaire_ttc: 0,
               transport: 0,
               transport_tva: 0.14,
               transport_ttc: 0,
               //todo
               magazinage: 0,
               magazinage_tva: 0,
               magazinage_ttc: 0,

               douane: 0,
               douane_tva: 0,
               douane_ttc: 0,

               surestaries: 0,
               surestaries_tva: 0.2,
               surestaries_ttc: 0,
               manutension: 0,
               manutension_tva: 0,
               manutension_ttc: 0,
               fret: 0,
               fret_tva: 0,
               fret_ttc: 0,
               autres: 0,
               autres_tva: 0,
               autres_ttc: 0,
               cout_revient: 0,
               cout_revient_tva: 0,
               cout_revient_ttc: 0,
            },
            date_facture: moment().format('DD/MM/YYYY'),
            types: ['INTERNATIONAL', 'NATIONAL'],
            providers: [],
            formErrors: '',
            messageErr: 'La remplissement de ce champs est obligatoire !',
            error: {
               facture: null
            }
         }
      },
      watch: {
         date_facture: function (d) {
            this.newArr.date_facture1 = moment(d).format('DD/MM/YYYY');
         },

         'inter.price_devise': function (v) {
            let x = parseFloat(v) * parseFloat(this.inter.cours_change);
            if (!isNaN(x))
               this.newArr.price_provider = this.round(x, 2);
            this.calculeCoutRevient();
         },
         'inter.cours_change': function (v) {
            let x = parseFloat(v) * parseFloat(this.inter.price_devise);
            if (!isNaN(x))
               this.newArr.price_provider = this.round(x, 2);
            this.calculeCoutRevient();
         },
         //----------------Transitaire-----------------------
         'inter.transitaire': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.transitaire_tva);
            if (!isNaN(x))
               this.inter.transitaire_ttc = this.round(x, 2);
            this.calculeCoutRevient();
         },
         'inter.transitaire_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.transitaire);
            if (!isNaN(x))
               this.inter.transitaire_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         //----------------transport-----------------------
         'inter.transport': function (v) {
            let x = parseFloat(v) * parseFloat(0.14);
            if (!isNaN(x)) {
               this.inter.transport_tva = (parseFloat(v) * 0.14);
               this.inter.transport_ttc = (this.inter.transport_tva + v)
            }
            this.calculeCoutRevient();
         },
         'inter.transport_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.transport);
            if (!isNaN(x))
               this.inter.transport_ttc = this.round(x, 2);
            this.calculeCoutRevient();
         },
         //----------------magazinage-----------------------
         'inter.magazinage': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.magazinage_tva);
            if (!isNaN(x))
               this.inter.magazinage_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         'inter.magazinage_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.magazinage);
            if (!isNaN(x))
               this.inter.magazinage_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         //----------------magazinage-----------------------
         'inter.douane': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.douane_tva);
            if (!isNaN(x))
               this.inter.douane_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         'inter.douane_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.douane);
            if (!isNaN(x))
               this.inter.douane_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },

         //----------------surestaries-----------------------
         'inter.surestaries': function (v) {
            let x = parseFloat(v) * parseFloat(0.2);
            if (!isNaN(x)) {
               this.inter.surestaries_tva = this.round(x, 2);
               this.inter.surestaries_ttc = this.round(x, 2) + v;
            }
            this.calculeCoutRevient();
         },
         'inter.surestaries_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.surestaries);
            if (!isNaN(x))
               this.inter.surestaries_ttc = this.round(x, 2);
            this.calculeCoutRevient();
         },
         //----------------manutension-----------------------
         'inter.manutension': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.manutension_tva);
            if (!isNaN(x))
               this.inter.manutension_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         'inter.manutension_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.manutension);
            if (!isNaN(x))
               this.inter.manutension_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         //----------------fret-----------------------
         'inter.fret': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.fret_tva);
            if (!isNaN(x))
               this.inter.fret_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         'inter.fret_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.fret);
            if (!isNaN(x))
               this.inter.fret_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         'inter.autres': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.autres_tva);
            if (!isNaN(x))
               this.inter.autres_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
         'inter.autres_tva': function (v) {
            let x = parseFloat(v) + parseFloat(this.inter.autres);
            if (!isNaN(x))
               this.inter.autres_ttc = this.round(x, 2)
            this.calculeCoutRevient();
         },
      },
      mounted() {
         axios.get('/api/providers', {
            params: {
               fields: 'id,steName'
            }
         }).then(res => {
            const {data} = res
            this.providers = data
         }).catch(err => console.log(err))

         if (this.$route.name === 'update_arrival') {

            axios.get(`/api/arrivals/${this.$route.params.id}`, {
               params: {with: 'product,user'}
            })
               .then(({data}) => {
                  console.log(data.product)
                  this.$emit('getProduct', data.product)
                  _.forEach(this.newArr, (v, k) => {
                     this.newArr[k] = data[k]
                     // console.log(k + ":" + data[k])
                  })
                  if (data['type'] === 'INTERNATIONAL') {
                     _.forEach(this.inter, (V, K) => {
                        this.inter[K] = data[K]
                     })
                  }
               })
               .catch(err => {
                  this.$notification.error('Cette Modification a perdu !!');
                  console.log(err.response);
                  this.$router.push({name: 'notFound'})
               })
         }

      },
      methods: {
         customFormatter(date) {
            return moment(date).format('DD/MM/YYYY');
         },
         round(value, decimals) {
            return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
         },
         inputsToFormData() {
            return {...this.newArr, ...this.inter, products: []}
         },
         validateBeforeSubmit() {

            return this.$validator.validateAll()
               .then((result) => {
                     if (result) {
                        const data = this.inputsToFormData()
                        this.$emit('getCharge', data);
                     }
                     return result
                  }
               );

         },
         calculeCoutRevient() {
            this.inter.cout_revient = Number(this.inter.transitaire) + Number(this.inter.transport) +
               Number(this.inter.magazinage) + Number(this.inter.surestaries) + Number(this.inter.manutension) +
               Number(this.inter.fret) + Number(this.inter.autres) + Number(this.inter.douane) + Number(this.newArr.price_provider);

            this.inter.cout_revient_tva = Number(this.inter.transitaire_tva) + Number(this.inter.transport_tva) +
               Number(this.inter.magazinage_tva) + Number(this.inter.surestaries_tva) + Number(this.inter.manutension_tva) +
               Number(this.inter.fret_tva) + Number(this.inter.autres_tva) + Number(this.inter.douane_tva);

            this.inter.cout_revient_ttc = Number(this.inter.transitaire_ttc) + Number(this.inter.transport_ttc) + Number(this.inter.magazinage_ttc) +
               Number(this.inter.surestaries_ttc) + Number(this.inter.manutension_ttc) + Number(this.inter.fret_ttc) + Number(this.inter.autres_ttc) +
               Number(this.inter.douane_ttc) + Number(this.newArr.price_provider);
            //Formatting Values
            this.inter.cout_revient = this.round(this.inter.cout_revient, 2);
            this.inter.cout_revient_tva = this.round(this.inter.cout_revient_tva, 2);
            this.inter.cout_revient_ttc = this.round(this.inter.cout_revient_ttc, 2);
         },
      },
      components: {
         BigTitle,
         CoolSelect, Datepicker
      }
   }
</script>

<style scoped>
   .half-cost {
      border-right: 1px dashed gray;
   }
</style>
