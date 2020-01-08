<template>
    <div class="segment" id="arrival_form">
        <form>
            <h3 class="text-uppercase text-center text-primary mb-3">Entete Facture</h3>
            <div class="dropdown-divider"></div>
            <div class="row">
                <div class="col" v-if="items.length>0">
                    <label for="">Fournisseur : </label>
                    <cool-select :items="items" item-text="steName" item-value="id" name="provider"
                                 v-model="provider_id" v-validate="'required'"/>
<!--                    <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('provider')"></i>-->
<!--                    <span class="help text-danger" v-show="errors.has('provider')">{{ messageErr }}</span>-->
                </div>
            </div>
            <div class="row ">
                <div class="col">
                    <div class="form-group">
                        <label for="">Numero Facture</label>
                        <input class="form-control" name="n_facture" type="text" v-model="n_facture"
                               v-validate="'required'"/>
<!--                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('n_facture')"></i>-->
<!--                        <span class="help text-danger" v-show="errors.has('n_facture')">{{ messageErr }}</span>-->
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Numero du dossier</label>
                        <input class="form-control" name="n_dossier" type="text" v-model="n_dossier"
                               v-validate="'required'">
<!--                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('n_dossier')"></i>-->
<!--                        <span class="help text-danger" v-show="errors.has('n_dossier')">{{ messageErr }}</span>-->
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col">
                    <div class="form-group">
                        <label for="">Type de l'arrivage :</label>
                        <cool-select :items="types" name="type"
                                     v-model="type" v-validate="'required'"/>
<!--                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('type')"></i>-->
<!--                        <span class="help text-danger" v-show="errors.has('type')">{{ messageErr }}</span>-->
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Date de la Facture :</label>
                        <datepicker :format="customFormatter" :language="fr" :monday-first="true"
                                    calendar-button-icon="fa fa-user" class="form-control" name="date_picker"
                                    v-model="date_facture"
                        />
                    </div>
                </div>
            </div>
            <div class="row " v-if="type=='INTERNATIONAL'">
                <div class="col">
                    <div class="form-group">
                        <label for="">Prix en devise :</label>
                        <input class="form-control" type="number" v-model="price_devise"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Cours de change :</label>
                        <input class="form-control" type="number" v-model="cours_change"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="">Prix fournisseur :</label>
                        <input :disabled="this.type==='INTERNATIONAL'" class="form-control" name="prix_fournisseur"
                               type="number" v-model="price_provider" v-validate="'required'"/>
<!--                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('prix_fournisseur')"></i>-->
<!--                        <span class="help text-danger" v-show="errors.has('prix_fournisseur')">{{ messageErr }}</span>-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
<!--                        <i class="fa fa-exclamation-triangle text-danger" v-show="formErrors != null"></i>-->
<!--                        <span class="help text-danger" v-show="formErrors != null">{{ formErrors }}</span>-->
                    </div>
                </div>
            </div>
        </form>
        <form name="chargesForm" v-show="type=='INTERNATIONAL'" ref="chargesForm">
            <h3 class="text-uppercase text-center text-primary mb-3">Partie des charges :</h3>
            <div class="dropdown-divider"></div>
            <div class="row" name="transitaire">
                <div class="col">
                    <div class="form-group">
                        <label for="">transitaire :</label>
                        <input class="form-control" type="number" v-model="transitaire"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">transitaire_tva :</label>
                        <input class="form-control" type="number" v-model="transitaire_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">transitaire_ttc :</label>
                        <input class="form-control" type="number" v-model="transitaire_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="transport">
                <div class="col">
                    <div class="form-group">
                        <label for="">transport :</label>
                        <input class="form-control" type="number" v-model="transport"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">transport_tva :</label>
                        <input class="form-control" type="number" v-model="transport_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">transport_ttc :</label>
                        <input class="form-control" type="number" v-model="transport_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="magazinage">
                <div class="col">
                    <div class="form-group">
                        <label for="">magazinage :</label>
                        <input class="form-control" type="number" v-model="magazinage"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">magazinage_tva :</label>
                        <input class="form-control" type="number" v-model="magazinage_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">magazinage_ttc :</label>
                        <input class="form-control" type="number" v-model="magazinage_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="surestaries">
                <div class="col">
                    <div class="form-group">
                        <label for="">surestaries :</label>
                        <input class="form-control" type="number" v-model="surestaries"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">surestaries_tva :</label>
                        <input class="form-control" type="number" v-model="surestaries_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">surestaries_ttc :</label>
                        <input class="form-control" type="number" v-model="surestaries_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="surestaries">
                <div class="col">
                    <div class="form-group">
                        <label for="">manutension :</label>
                        <input class="form-control" type="number" v-model="manutension"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">manutension_tva :</label>
                        <input class="form-control" type="number" v-model="manutension_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">manutension_ttc :</label>
                        <input class="form-control" type="number" v-model="manutension_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="fret">
                <div class="col">
                    <div class="form-group">
                        <label for="">fret :</label>
                        <input class="form-control" type="number" v-model="fret"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">fret_tva :</label>
                        <input class="form-control" type="number" v-model="fret_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">fret_ttc :</label>
                        <input class="form-control" type="number" v-model="fret_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="fret">
                <div class="col">
                    <div class="form-group">
                        <label for="">autres :</label>
                        <input class="form-control" type="number" v-model="autres"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">autres_tva :</label>
                        <input class="form-control" type="number" v-model="autres_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">autres_ttc :</label>
                        <input class="form-control" type="number" v-model="autres_ttc"/>
                    </div>
                </div>
            </div>
            <div class="row" name="cout_revient">
                <div class="col">
                    <div class="form-group">
                        <label for="">cout_revient :</label>
                        <input class="form-control" disabled type="number" v-model="cout_revient"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">cout_revient_tva :</label>
                        <input class="form-control" disabled type="number" v-model="cout_revient_tva"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">cout_revient_ttc :</label>
                        <input class="form-control" disabled type="number" v-model="cout_revient_ttc"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {CoolSelect} from 'vue-cool-select'
    import moment from 'moment'
    import Datepicker from 'vuejs-datepicker';
    import {fr} from 'vuejs-datepicker/dist/locale'

    import {mapGetters} from 'vuex'

    export default {
        name: "ArrivalForm",
        data() {
            return {
                fr,
                provider_id: null,
                items: [],
                n_facture: '',
                n_dossier: '',
                date_facture: moment().format(),
                date_facture1: moment().format('DD/MM/YYYY'),
                price_devise: '',
                cours_change: '',
                price_provider: '',
                type: '',
                types: ['INTERNATIONAL', 'NATIONAL'],
                messageErr: 'La remplissement de ce champs est obligatoire !',
                formErrors: null,
                //Variables des charges
                transitaire: null,
                transitaire_tva: null,
                transitaire_ttc: null,
                transport: null,
                transport_tva: null,
                transport_ttc: null,
                //todo
                magazinage: null,
                magazinage_tva: null,
                magazinage_ttc: null,
                surestaries: null,
                surestaries_tva: null,
                surestaries_ttc: null,
                manutension: null,
                manutension_tva: null,
                manutension_ttc: null,
                fret: null,
                fret_tva: null,
                fret_ttc: null,
                autres: null,
                autres_tva: null,
                autres_ttc: null,
                cout_revient: null,
                cout_revient_tva: null,
                cout_revient_ttc: null,
                token: '',
                // user_id: null
            }

        },
        watch: {
            date_facture: function (d) {
                this.date_facture1 = moment(d).format('DD/MM/YYYY');
            },
            //----------------Devise-----------------------
            price_devise: function (v) {
                let x = parseFloat(v) * parseFloat(this.cours_change);
                if (!isNaN(x))
                    this.price_provider = this.round(x, 2);
            },
            cours_change: function (v) {
                let x = parseFloat(v) * parseFloat(this.price_devise);
                if (!isNaN(x))
                    this.price_provider = this.round(x, 2)
            },
            //----------------Transitaire-----------------------
            transitaire: function (v) {
                let x = parseFloat(v) + parseFloat(this.transitaire_tva);
                if (!isNaN(x))
                    this.transitaire_ttc = this.round(x, 2);
                this.calcleCoutRevient();
            },
            transitaire_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.transitaire);
                if (!isNaN(x))
                    this.transitaire_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            //----------------transport-----------------------
            transport: function (v) {
                let x = parseFloat(v) * parseFloat('0.14');
                if (!isNaN(x))
                    this.transport_tva = this.round(x, 2);
                this.calcleCoutRevient();
            },
            transport_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.transport);
                if (!isNaN(x))
                    this.transport_ttc = this.round(x, 2);
                this.calcleCoutRevient();

            },
            //----------------magazinage-----------------------
            magazinage: function (v) {
                let x = parseFloat(v) + parseFloat(this.magazinage_tva);
                if (!isNaN(x))
                    this.magazinage_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            magazinage_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.magazinage);
                if (!isNaN(x))
                    this.magazinage_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            //----------------surestaries-----------------------
            surestaries: function (v) {
                let x = parseFloat(v) * parseFloat('0.2');
                if (!isNaN(x))
                    this.surestaries_tva = this.round(x, 2);
                this.calcleCoutRevient();
            },
            surestaries_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.surestaries);
                if (!isNaN(x))
                    this.surestaries_ttc = this.round(x, 2);
                this.calcleCoutRevient();
            },
            //----------------manutension-----------------------
            manutension: function (v) {
                let x = parseFloat(v) + parseFloat(this.manutension_tva);
                if (!isNaN(x))
                    this.manutension_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            manutension_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.manutension);
                if (!isNaN(x))
                    this.manutension_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            //----------------fret-----------------------
            fret: function (v) {
                let x = parseFloat(v) + parseFloat(this.fret_tva);
                if (!isNaN(x))
                    this.fret_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            fret_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.fret);
                if (!isNaN(x))
                    this.fret_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            //----------------autres-----------------------
            autres: function (v) {
                let x = parseFloat(v) + parseFloat(this.autres_tva);
                if (!isNaN(x))
                    this.autres_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            autres_tva: function (v) {
                let x = parseFloat(v) + parseFloat(this.autres);
                if (!isNaN(x))
                    this.autres_ttc = this.round(x, 2)
                this.calcleCoutRevient();
            },
            //----------------cout_revient-----------------------
            // cout_revient: function (v) {
            //     let x = parseFloat(v) + parseFloat(this.cout_revient_tva);
            //     if (!isNaN(x))
            //         this.cout_revient_ttc = this.round(x,2)
            // },
            // cout_revient_tva: function (v) {
            //     let x = parseFloat(v) + parseFloat(this.cout_revient);
            //     if (!isNaN(x))
            //         this.cout_revient_ttc = this.round(x,2)
            // },
            //---------------------------------
            type: function () {
                this.cours_change = '';
                this.price_devise = '';
                this.price_provider = '';
                this.reset();
            },
        },
        mounted() {
            axios.get('/api/providers',
                {
                    params: {
                        fields: 'id,steName'
                    }
                }).then(res => {
                const ste = res.data
                console.log(ste)
                this.items = ste
            }).catch(err => console.log(err))
        }
        ,
        methods: {
            ...mapGetters({
                // user_id: 'getId',
                // token: 'getToken',
            }),
            reset() {
                // console.log(this.$refs.chargesForm.forEach(t,this.$refs.chargesForm.));
                // alert('sss');
            },
            calcleCoutRevient() {
                this.cout_revient = Number(this.transitaire) + Number(this.transport) + Number(this.magazinage) + Number(this.surestaries) + Number(this.manutension) + Number(this.fret) + Number(this.autres);
                this.cout_revient_tva = Number(this.transitaire_tva) + Number(this.transport_tva) + Number(this.magazinage_tva) + Number(this.surestaries_tva) + Number(this.manutension_tva) + Number(this.fret_tva) + Number(this.autres_tva);
                this.cout_revient_ttc = Number(this.transitaire_ttc) + Number(this.transport_ttc) + Number(this.magazinage_ttc) + Number(this.surestaries_ttc) + Number(this.manutension_ttc) + Number(this.fret_ttc) + Number(this.autres_ttc);
                this.cout_revient = this.round(this.cout_revient,2);
                this.cout_revient_tva = this.round(this.cout_revient_tva,2);
                this.cout_revient_ttc = this.round(this.cout_revient_ttc,2);
                },
            round(value, decimals) {
                return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
            },
            customFormatter(date) {
                return moment(date).format('DD/MM/YYYY');
            },
            validateBeforeSubmit() {
                // return true;
                return this.$validator.validateAll().then((result) => {
                    if (result) {
                        const formData = this.inputsToFormData();
                        const tokenrise = `Bearer ${this.token}`
                        const _this = this;
                        // formData.append('user_id', this.user_id)
                        return axios.post('/api/arrivals', formData)
                            .then(res => {
                                _this.$emit('getArrivalId', res.data.id);
                                _this.$emit('getArrivalId',formData);
                                _this.$emit('getCharge',formData);
                                return true
                            })
                            .catch(err => {
                                this.formErrors = "Impossible d'ajouter cet enregistrement, contactez votre administrateur ";
                                console.log(err.response)
                                return false;
                            });
                    }
                    return false;
                });
            },
            inputsToFormData(){
                const formData = new FormData();
                formData.append('n_dossier', this.n_dossier);
                formData.append('n_facture', this.n_facture);
                formData.append('price_devise', this.price_devise);
                formData.append('cours_change', this.cours_change);
                formData.append('date_facture', this.date_facture1);
                formData.append('type', this.type);
                formData.append('price_provider', this.price_provider);
                formData.append('provider_id', this.provider_id);
                if (this.type == 'INTERNATIONAL') {
                    formData.append('transitaire', this.transitaire);
                    formData.append('transitaire_tva', this.transitaire_tva);
                    formData.append('transitaire_ttc', this.transitaire_ttc);
                    formData.append('transport', this.transport);
                    formData.append('transport_tva', this.transport_tva);
                    formData.append('transport_ttc', this.transport_ttc);
                    formData.append('magazinage', this.magazinage);
                    formData.append('magazinage_tva', this.magazinage_tva);
                    formData.append('magazinage_ttc', this.magazinage_ttc);
                    formData.append('surestaries', this.surestaries);
                    formData.append('surestaries_tva', this.surestaries_tva);
                    formData.append('surestaries_ttc', this.surestaries_ttc);
                    formData.append('manutension', this.manutension);
                    formData.append('manutension_tva', this.manutension_tva);
                    formData.append('manutension_ttc', this.manutension_ttc);
                    formData.append('fret', this.fret);
                    formData.append('fret_tva', this.fret_tva);
                    formData.append('fret_ttc', this.fret_ttc);
                    formData.append('autres', this.autres);
                    formData.append('autres_tva', this.autres_tva);
                    formData.append('autres_ttc', this.autres_ttc);
                    formData.append('cout_revient', this.cout_revient);
                    formData.append('cout_revient_tva', this.cout_revient_tva);
                    formData.append('cout_revient_ttc', this.cout_revient_ttc);
                }
                return formData;
            }
            // getArrivalId(){
            //     this.$emit('getArrivalId',this.formDATA);
            // }
        }
        ,
        components: {
            CoolSelect, Datepicker
        }
    }
</script>

<style scoped>
    input[name="date_picker"] {
        width: 100%;
    }
</style>
