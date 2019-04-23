<template>
    <div id="provider-list">
        <h3 class="text-lg-center text-secondary m-5">Liste des fournisseurs</h3>
        <div class="dropdown-divider"></div>
        <div class="row justify-content-around">
            <h5 class="text-uppercase text-secondary m-3"> Parametre de rechercher </h5>
            <div class="col-8">
                <form @submit.prevent="">
                    <div class="input-group m-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Filter Par :</span>
                            <div class="input-group-text">
                                <input type="radio" name="opt-filter" value="firstName" v-model="opt"
                                       v-on:change="EmptyText">
                                <span class="ml-1">Nom</span>
                            </div>
                            <div class="input-group-text">
                                <input type="radio" name="opt-filter" value="steName" v-model="opt"
                                       v-on:change="EmptyText">
                                <span class="ml-1">Societe</span>
                            </div>
                        </div>

                        <input type="text" class="form-control" v-model="searchTxt" @input="filter"
                               placeholder="Recherche par Societe"
                               autofocus>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success " type="button" id="button-addon2">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Societe</th>
                <th scope="col">TVA</th>
                <th scope="col">Numero Siret</th>
                <th scope="col">Nom</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="searchProviders === null">
                <td colspan="6">
                    <div class="row justify-content-center">
                        <div class="col-2">
                            <loading :animation-duration="6000" :size="40" color="#ff1d5e"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-else-if="searchProviders.length === 0">
                <td colspan="6">
                    <h6 class="text-center text-secondary m-5">Pas de fournisseur dans cette cas (Vide...) </h6>
                </td>
            </tr>
            <tr v-else v-for="(p,index) in searchProviders">
                <th scope="row">{{index}}</th>
                <td>{{p.steName}}</td>
                <td>{{p.numTva}}</td>
                <td>{{p.numSiret}}</td>
                <td>{{p.firstName}} {{p.lastName}}</td>
                <td>
                    <button class="btn btn-sm btn-success">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {SelfBuildingSquareSpinner} from 'epic-spinners'

    export default {
        name: "ProvidersList",
        data() {
            return {
                providers: null,
                searchProviders: null,
                searchTxt: '',
                opt: 'steName',
            }
        },
        mounted() {
            axios.get('/api/providers')
                .then(res => {
                    const data = res.data
                    this.providers = data
                    this.searchProviders = data
                })
                .catch(err => console.log(err.response))
        },
        methods: {
            filter() {
                const ste = this.searchTxt
                const val = this.providers

                if (this.opt === 'steName')
                    this.searchProviders = _.filter(val, o => {
                        return _.startsWith(_.toUpper(o.steName), _.toUpper(ste))
                    });
                else
                    this.searchProviders = _.filter(val, o => {
                        return _.startsWith(_.toUpper(o.firstName), _.toUpper(ste))
                    });
            },
            EmptyText() {
                this.searchTxt = ''
            }
        },
        components: {
            'loading': SelfBuildingSquareSpinner
        }
    }
</script>

<style scoped>

</style>
