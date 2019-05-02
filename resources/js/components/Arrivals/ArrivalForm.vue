<template>
    <div id="arrival_form" class="segment">
        <form>
            <h3 class="text-uppercase text-center text-primary mb-3">Entete Facture</h3>
            <div class="dropdown-divider"></div>
            <div class="row">
                <div class="col" v-if="items.length>0">
                    <label for="">Fournisseur : </label>
                    <cool-select v-model="provider_id" :items="items" item-value="id" item-text="steName"/>
                </div>
            </div>
            <div class="row ">
                <div class="col">
                    <div class="form-group">
                        <label for="">Numero Facture</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Numero du dossier</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col">
                    <div class="form-group">
                        <label for="">Type de l'arrivage :</label>
                        <cool-select v-model="type" :items="types"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="">Date de la Facture :</label>
                        <datepicker v-model="date_facture" name="date_picker" :format="customFormatter"
                                    class="form-control" :language="fr" :monday-first="true" calendar-button-icon="fa fa-user"
                        />
                        <!--                        <date-pick v-model="date_facture" :format="'DD/MM/YYYY'" :months="months" :weekdays="weekdays" class="form-control"/>-->
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {CoolSelect} from 'vue-cool-select'
    // import DatePick from 'vue-date-pick';
    // import 'vue-date-pick/dist/vueDatePick.css';
    import moment from 'moment'
    import Datepicker from 'vuejs-datepicker';
    import {fr} from 'vuejs-datepicker/dist/locale'

    export default {
        name: "ArrivalForm",
        data() {
            return {
                fr,
                provider_id: null,
                items: [],
                n_facture: '',
                n_dossier: '',
                date_facture: moment().format('DD/MM/YYYY'),
                date_facture1: moment().format('DD/MM/YYYY'),
                price_devise: '',
                cours_change: '',
                type: '',
                types: ['INTERNATIONAL', 'NATIONAL', 'INTERNE'],

                weekdays: moment.localeData('fr').weekdaysShort(),
                months: moment.localeData('fr').months()
            }

        },
        watch: {
            date_facture: function (d) {
                this.date_facture1 = moment(d).format('DD/MM/YYYY');
            }

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
            customFormatter(date) {
                return moment(date).format('L');
            }
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
