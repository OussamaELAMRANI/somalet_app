<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <!--        <div class="container">-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Acceuil
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ user.username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <li class="dropdown-item text-center">
                            <img style="height: 120px;width: 120px;" src="https://picsum.photos/200/300"
                                 alt="photo d'utilisateur" class="rounded-circle mx-auto d-block mb-2">
                            <p>
                                {{ user.first_name }} - {{ user.last_name }}
                            <p>
                                <small>{{ user.type_user }}</small>
                            </p>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="" style="display: inline-list-item">
                            <div style="float: left; display: block">
                                <a href="#" class="btn btn-light">Profile</a>
                            </div>
                            <div style="float: right; display: block">
                                <a href="#" class="btn btn-light" @click="logout">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
        <!--        </div>-->
    </nav>
</template>

<script>
    import store from "@/store"
    import {mapActions} from 'vuex'

    export default {
        name: "NavBar",
        methods: {
            // ...mapGetters({
            //     username:'userStore/name',
            //     role:'getRole',
            //     first_name:'getFirstName',
            //     last_name:'getLastName',
            // }),
            logout() {
                this.logOut()
                    .then(() => {
                        this.$router.push('/login')
                    })
            },
            ...mapActions({logOut: 'logout'}),
        },
        computed: {
            user() {
                return store.getters.user
            }
        }
    }
</script>

<style scoped>

</style>
