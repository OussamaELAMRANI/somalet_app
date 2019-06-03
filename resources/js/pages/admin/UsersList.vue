<template>
    <div id="users_list" class="container">
        <alert-modal title="Supperession d'un Utilisateur" id="bitch">
            <template slot="content">
                <h5 class="text-uppercase text-danger text-center">
                    Voullez-vous vraiment supprimer cet utilisateur ?
                </h5>
            </template>
            <template slot="btn">
                <button class="btn btn-danger" @click="supp()"  data-dismiss="modal">Supprimer</button>
            </template>
        </alert-modal>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl" role="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail d'un Utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert segment">
                            <show-user :user="user"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="text-lg-center text-secondary m-5">Liste des Utilisateurs</h3>
        <div class="dropdown-divider"></div>

        <div class="row justify-content-around ">
            <h5 class="text-uppercase text-secondary m-3"> Parametre de rechercher </h5>
            <div class="col-8">
                <form @submit.prevent="">
                    <div class="input-group m-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-primary" id="inputGroup-sizing-sm">Filter Par :</span>
                            <div class="input-group-text bg-warning">
                                <input type="radio" name="opt-filter" value="login" v-model="opt"
                                       v-on:change="EmptyText">
                                <span class="ml-1">Login</span>
                            </div>
                            <div class="input-group-text bg-success text-white">
                                <input type="radio" name="opt-filter" value="name" v-model="opt"
                                       v-on:change="EmptyText">
                                <span class="ml-1">Nom utilisateur</span>
                            </div>
                        </div>

                        <input type="text" class="form-control" v-model="searchTxt" @input="filter"
                               placeholder="Recherche ..."
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


        <table class="table table-hover table-striped" v-if="users">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Login</th>
                <th scope="col">Nom</th>
                <th scope="col">Prenom</th>
                <th scope="col">Type</th>
                <th scope="col">Active</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="searchUser === null">
                <td colspan="6">
                    <div class="row justify-content-center">
                        <div class="col-2">
                            <loading :animation-duration="6000" :size="40" color="#ff1d5e"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-else-if="searchUser.length === 0">
                <td colspan="10">
                    <h6 class="text-center text-secondary m-5">Pas d'Utilisateur dans ce cas (Vide...) </h6>
                </td>
            </tr>
            <tr v-else v-for="(p,index) in searchUser">
                <th scope="row">{{index+1}}</th>
                <td>{{p.username}}</td>
                <td>{{p.first_name}}</td>
                <td>{{p.last_name}}</td>
                <td>{{p.type_user}}</td>
                <td>
                    <button disabled class="btn btn-success" v-if="p.is_active === 1">activé</button>
                    <button disabled class="btn btn-danger" v-if="p.is_active === 0">desactivé</button>
                </td>
                <td>
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal"
                            @click="show(p.id)">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </button>
<!--                    <button  class="btn btn-sm btn-success"-->
<!--                             data-toggle="modal" data-target="#exampleModal"-->
<!--                             @click="redirect(p.id)">-->
<!--                        <i class="fa fa-edit" aria-hidden="true"></i>-->
<!--                    </button>-->
                    <button class="btn btn-sm btn-danger"
                            @click="getElementIdToDelete(p.id)"
                            data-target="#bitch" data-toggle="modal">
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
    import AlertModal from "@/components/Modals/AlertModal";
    import ShowUser from "./showUser";

    export default {
        name: "UsersList",
        data() {
            return {
                ElementIdToDelete:null,
                users: null,
                searchUser: null,
                searchTxt: '',
                opt: 'login',
                user: {},
            }
        },
        mounted() {
            axios.get('/api/auth/allUsers')
                .then(res => {
                    const data = res.data
                    this.users = data
                    this.searchUser = data
                })
                .catch(err => console.log(err.response))
        },
        methods: {
            getElementIdToDelete(id){
                this.ElementIdToDelete = id;
            },
            supp(){
                let id = this.ElementIdToDelete;
                axios.delete(`/api/auth/user/${id}/delete`)
                    .then(res => {
                        // this.$notification.error('utilisateur bien supprimer')
                        // this.$router.push({name:'listUser',params:{id}});
                        this.getUsers();
                    })
                    .catch(err => {
                        // this.$notification.error("Ce utilisateur n'existe pas !")
                        // this.$notification.error('impossible de supprimer ce utilisateur')
                        // this.$router.push('/404')
                    })
            },
            show(id) {
                // console.log(id)
                const val = this.users
                this.user = _.find(val, o => {
                    return o.id == id
                });
            },
            getUsers(){
                this.users = null;
                axios.get('/api/auth/allUsers')
                    .then(res => {
                        const data = res.data
                        this.users = data
                        this.searchUser = data
                    })
                    .catch(err => console.log(err.response))
            },
            // redirect(id){
            //     this.$router.push({name:'updateUser',params:{id}});
            // },
            filter() {
                const ste = this.searchTxt
                const val = this.users

                if (this.opt === 'login')
                    this.searchUser = _.filter(val, o => {
                        return _.startsWith(_.toUpper(o.username), _.toUpper(ste))
                    });
                else
                    this.searchUser = _.filter(val, o => {
                        return _.startsWith(_.toUpper(o.first_name), _.toUpper(ste))
                    });
            },
            EmptyText() {
                this.searchTxt = ''
            },
            // show(id) {
            //     console.log(id)
            //     const val = this.providers
            //     this.provider = _.find(val, o => {
            //         return o.id == id
            //     });
            // }
        },
        components: {
            AlertModal,
            ShowUser,
            'loading': SelfBuildingSquareSpinner
        }
    }
</script>

<style scoped>

</style>
