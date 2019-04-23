<template>
    <div id="provider-list">
        <h3 class="text-lg-center text-secondary m-5">Liste des fournisseurs</h3>
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
            <tr v-if="providers === null">
                <td colspan="6">
                    <div class="row justify-content-center">
                        <div class="col-2">
                            <loading :animation-duration="6000" :size="40" color="#ff1d5e"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-else-if="providers.length === 0">
                <td colspan="6">Vide ...</td>
            </tr>
            <tr v-else v-for="(p,index) in providers">
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
                providers: null
            }
        },
        mounted() {
            axios.get('/api/providers')
                .then(res => {
                    this.providers = res.data
                })
                .catch(err => console.log(err.response))
        },
        components: {
            'loading': SelfBuildingSquareSpinner
        }
    }
</script>

<style scoped>

</style>
