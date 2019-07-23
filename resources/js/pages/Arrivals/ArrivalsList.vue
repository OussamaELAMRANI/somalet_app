<template>
    <div id="arrivals_list">
        <div class="row">
            <h4 class="text-uppercase m-3"> Liste des arrivages</h4>
            <div class="dropdown-divider"></div>
            <router-link :to="{name:'new_arrival'}" class="btn btn-outline-success float-right m-3">
                <i class="fa fa-plus"></i>
                Nouveau arrivage
            </router-link>

            <div class="col-9 m-0 pr-0">

                <table class="table bg-success text-white" id="head-nav">
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
                         v-for="(c,index) in searchArrivals" :key="index"
                         :id="c.id">
                        <div class="col-1">{{c.n_facture}}</div>
                        <div class="col-1">{{c.n_dossier}}</div>
                        <div class="col-2">{{c.date_facture}}</div>
                        <div class="col-2">
                            <span class="badge badge-warning">{{c.type}}</span>
                        </div>
                        <div class="col-2">{{c.price_provider}} DH</div>
                        <div class="col-2">
                            <span class="badge badge-primary">
                            En Attent
                            </span>
                        </div>
                        <div class="col-1">{{c.user.username}}</div>
                        <div class="col-1">
                            <button class="btn btn-sm btn-success rounded-circle" data-toggle="modal"
                                    data-target="#exampleModal"
                                    @click="getArrival(c)">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-sm btn-danger rounded-circle" @click="getIdToDelete(c.id)"
                                    data-target="#bitch" data-toggle="modal">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-sm btn-primary rounded-circle" @click="toUpdate(c.id)">
                                <i class="fa fa-pen" aria-hidden="true"></i>
                            </button>
                        </div>
                        <template v-if="c.product.length >0">
                            <div class="col-1">
                                <button class="btn rounded-circle btn-sm btn-dark" data-toggle="collapse"
                                        :data-target="'#collapse_'+c.id" aria-expanded="false"
                                        :aria-controls="'collapse_'+c.id">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class=" col-12 dropdown-divider"></div>
                            <div class="col-12 mt-2 justify-content-center collapse" :id="'collapse_'+c.id">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-dark text-center table-bordered">
                                    <tr>
                                        <th scope="col">Produit</th>
                                        <th scope="col">Qte</th>
                                        <th scope="col">Prix UT</th>
                                        <th scope="col">Prix TTC</th>
                                        <th scope="col">Fret douane</th>
                                        <th scope="col">ETAT de reception</th>
                                        <th scope="col-2">Remaque</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="p in c.product">
                                        <td>{{p.name.slice(0,20)}}</td>
                                        <td>{{p.pivot.qte_facture}}</td>
                                        <td>{{p.pivot.price_unit_ht}}</td>
                                        <td>{{p.pivot.price_devise}}</td>
                                        <td>{{p.pivot.fret_douane}}</td>
                                        <td>{{(p.pivot.date_reception) === null ? 'en Attent' : 'Receptionner'}}</td>
                                        <td>{{p.pivot.remark}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </template>


                    </div>

                </div>

            </div>
            <!--  FILTER -->
            <div class="col-3 m-0">
                <form @submit.prevent="filterArrival">

                    <div class="segment bg-filter text-white">
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
</template>

<script>
    import {SelfBuildingSquareSpinner} from 'epic-spinners'
    import Datepicker from 'vuejs-datepicker';
    import {fr} from 'vuejs-datepicker/dist/locale'
    import moment from 'moment'

    export default {
        name: "ArrivalsList",
        data() {
            return {
                fr,
                factorType: 'NATIONAL',
                // types: ['NATIONAL', 'INTERNATONAL'],
                search: {
                    n_fature: '',
                    n_dossier: '',
                    date_facture: moment().format('DD/MM/YYYY'),
                },
                arrivals: [],
                searchArrivals: []
            }
        },
        mounted() {
            axios.get('/api/arrivals', {params: {with: 'user,provider,product'}})
                .then(res => {
                    const {data} = res
                    this.arrivals = data
                    this.searchArrivals = data
                })
                .catch(err => console.log(err.response))

            document.addEventListener('scroll', this.fixNav)
        },
        methods: {
            fixNav() {
                const scoX = window.scrollY
                const nav = document.getElementById('head-nav');
                if (scoX >= 200)
                    nav.classList.add('head-nav__fix')
                else
                    nav.classList.remove('head-nav__fix')
            },
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
            toUpdate(id) {
                this.$router.push(
                    {
                        name: 'update_arrival',
                        params: {id}

                    })
            },
        },
        components: {
            'loading': SelfBuildingSquareSpinner,
            Datepicker
        }
    }
</script>

<style lang="scss" scoped>
    .bg-filter {
        background: #9c57f8
    }

    #head-nav {
        background: #9c57f8;
    }
    .head-nav__fix {
        box-shadow: 0 2px 4px rgba(156, 87, 248, 0.5);
        max-width: 72%;
        top: 0;
        z-index: 100;
        background: #9c5;
        position: fixed;
    }
</style>
