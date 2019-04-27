<template>
    <div class="container">

        <form @submit.prevent="signUp" class="segment">
            <div class="form-group">

                <label for="username">Login</label>
                <input type="text" class="form-control" id="username" placeholder="Enter votre Login" v-model="username"
                       autofocus>
                <!--            <small id="emailHelp" class="form-text text-muted">Ne pas partager votre email</small>-->

                <!--            <div class="alert alert-danger" v-if="errors.email">-->
                <!--                {{errors.email}}-->
                <!--            </div>-->
                <div class="dropdown-divider"></div>

            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="fname">Prenom</label>
                        <input type="text" class="form-control" id="fname" placeholder="Prenom .." v-model="first_name">
                    </div>
                    <div class="col">
                        <label for="lname">Nom</label>
                        <input type="text" class="form-control" id="lname" placeholder="Prenom .." v-model="last_name">
                    </div>
                </div>

                <!--            <div class="alert alert-danger" v-if="errors.password">-->
                <!--                {{errors.password}}-->
                <!--            </div>-->
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="psswd">Password</label>
                        <input type="password" class="form-control" id="psswd" placeholder="Mot de passe .."
                               v-model="password">
                    </div>
                    <div class="col">
                        <label for="psswd2">Password</label>
                        <input type="password" class="form-control" id="psswd2" placeholder="Mot de passe .."
                               v-model="password2">
                    </div>
                </div>

                <!--            <div class="alert alert-danger" v-if="errors.password">-->
                <!--                {{errors.password}}-->
                <!--            </div>-->
            </div>
            <div class="dropdown-divider"></div>
            <!--        <div class="alert alert-danger" v-if="errors.error">-->
            <!--            {{errors.error}}-->
            <!--        </div>-->
            <div class="dropdown-divider"></div>

            <button type="submit" class="btn btn-primary">Enregister</button>

        </form>
    </div>

</template>

<script>
    export default {
        name: "SignUp",
        data() {
            return {
                username: null,
                first_name: null,
                last_name: null,
                password: null,
                password2: null,
            }
        },
        methods: {
            signUp() {
                axios.post('/api/auth/sign-up', {
                    'username': this.username,
                    'first_name': this.first_name,
                    'last_name': this.last_name,
                    'password': this.password,
                    'password_confirmation': this.password2,
                })
                    .then(res => {
                        alert(res.data)
                        this.$router.push('/login')
                    })
                    .catch(err => {
                        console.log(err.response)
                    })
            }
        }
    }
</script>

<style scoped>

</style>
